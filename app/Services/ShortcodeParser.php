<?php

namespace App\Services;

use App\Models\MediaFile;

class ShortcodeParser
{
    /**
     * Parse all shortcodes in article content.
     */
    public static function parse(?string $content): string
    {
        if (empty($content)) {
            return '';
        }
        // 1. Single Inline Image: [image src="..." width="50%" align="center" caption="..." alt="..."]
        $content = preg_replace_callback('/\[image\s+([^\]]+)\]/i', function ($matches) {
            $attrs = static::parseAttributes($matches[1]);
            $rawSrc = static::sanitizeUrl($attrs['src'] ?? '');
            if ($rawSrc === '') {
                return '';
            }
            $src = htmlspecialchars($rawSrc, ENT_QUOTES, 'UTF-8');
            $rawWidth = trim($attrs['width'] ?? '100%');
            $width = preg_match('/^\d+(?:\.\d+)?(?:%|px|rem|em|vw)$/', $rawWidth) ? $rawWidth : '100%';
            $rawAlign = strtolower(trim($attrs['align'] ?? 'center'));
            $align = in_array($rawAlign, ['left', 'right', 'center'], true) ? $rawAlign : 'center';
            $caption = htmlspecialchars($attrs['caption'] ?? '', ENT_QUOTES, 'UTF-8');
            $alt = htmlspecialchars($attrs['alt'] ?? ($attrs['caption'] ?? ''), ENT_QUOTES, 'UTF-8');

            $margin = '24px auto';
            $float = 'none';
            if ($align === 'left') {
                $margin = '0 24px 20px 0';
                $float = 'left';
            } elseif ($align === 'right') {
                $margin = '0 0 20px 24px';
                $float = 'right';
            }

            return '<figure class="article-figure" style="max-width: '.$width.'; margin: '.$margin.'; float: '.$float.'; text-align: center;">'
                .'<img src="'.$src.'" alt="'.$alt.'" data-lightbox-src="'.$src.'" data-lightbox-caption="'.$caption.'" class="zoomable-image" loading="lazy" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 18px rgba(0,0,0,0.08); cursor: pointer;" onclick="openSingleImageLightbox(this.getAttribute(\'data-lightbox-src\'), this.getAttribute(\'data-lightbox-caption\'))">'
                .($caption ? '<figcaption style="font-size: 13px; color: var(--sand, #b98550); font-style: italic; margin-top: 8px; font-family: var(--dev, serif);">'.$caption.'</figcaption>' : '')
                .'</figure>';
        }, $content);

        // 2. Multi-Image Magazine / Facebook Mosaic Gallery: [gallery images="url1,url2" caption="..."]
        $content = preg_replace_callback('/\[gallery\s+([^\]]+)\]/i', function ($matches) {
            $attrs = static::parseAttributes($matches[1]);
            $imagesStr = $attrs['images'] ?? ($attrs['ids'] ?? '');
            $caption = htmlspecialchars($attrs['caption'] ?? '', ENT_QUOTES, 'UTF-8');

            $rawUrls = array_values(array_filter(array_map(function ($u) {
                return trim($u, " \t\n\r\0\x0B\"'");
            }, preg_split('/[\r\n,]+/', $imagesStr))));

            if (empty($rawUrls)) {
                return '';
            }

            $urls = [];
            foreach ($rawUrls as $url) {
                if (is_numeric($url)) {
                    $media = MediaFile::find($url);
                    $url = $media ? $media->url : '';
                }
                $safeUrl = static::sanitizeUrl($url);
                if ($safeUrl !== '') {
                    $urls[] = $safeUrl;
                }
            }

            if (empty($urls)) {
                return '';
            }

            $count = count($urls);
            $layoutClass = 'count-'.min($count, 5);
            $galleryId = 'fb_gal_'.substr(md5(implode('|', $urls)), 0, 8);
            $jsonUrls = htmlspecialchars(json_encode($urls, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');

            $html = '<div class="fb-mosaic-wrapper magazine-gallery-wrap">';
            $html .= '<div class="fb-mosaic-container '.$layoutClass.'" id="'.$galleryId.'" data-gallery-images="'.$jsonUrls.'">';

            $displayCount = min($count, 5);
            for ($i = 0; $i < $displayCount; $i++) {
                $itemClass = 'item-'.($i + 1).'-of-'.$displayCount;
                $imgUrl = htmlspecialchars($urls[$i], ENT_QUOTES, 'UTF-8');
                $isLastOverlay = ($count > 5 && $i === 4);

                $html .= '<div class="fb-mosaic-item '.$itemClass.'" onclick="openFbGallery(\''.$galleryId.'\', '.$i.')" title="Click to view full photo">';
                $html .= '<img src="'.$imgUrl.'" alt="Photo '.($i + 1).'" class="fb-mosaic-img" loading="lazy">';
                if ($isLastOverlay) {
                    $html .= '<div class="fb-mosaic-overlay">+'.($count - 4).'</div>';
                }
                $html .= '</div>';
            }

            $html .= '</div>';
            if ($caption) {
                $html .= '<div class="fb-mosaic-caption">'.$caption.'</div>';
            }
            $html .= '</div>';

            return $html;
        }, $content);

        // 3. Editorial Pull-Quote: [quote speaker="..." designation="..."]...[/quote]
        $content = preg_replace_callback('/\[quote(?:\s+speaker=["\']([^"\']*)["\'])?(?:\s+designation=["\']([^"\']*)["\'])?\](.*?)\[\/quote\]/is', function ($matches) {
            $speaker = htmlspecialchars($matches[1] ?? '', ENT_QUOTES, 'UTF-8');
            $designation = htmlspecialchars($matches[2] ?? '', ENT_QUOTES, 'UTF-8');
            $quote = htmlspecialchars(trim($matches[3] ?? ''), ENT_QUOTES, 'UTF-8');

            return '<blockquote class="editorial-pull-quote" style="margin: 36px 0; padding: 24px 32px; border-left: 4px solid var(--crimson, #8f1719); background: #fbf9f5; border-radius: 0 12px 12px 0; clear: both;">'
                .'<p style="font-family: var(--dev, serif); font-size: 21px; line-height: 1.6; color: var(--deep, #16100d); font-style: italic; margin: 0 0 12px;">“'.$quote.'”</p>'
                .($speaker ? '<div style="font-family: var(--sans, sans-serif); font-size: 13px; font-weight: 700; color: var(--sand, #b98550);">'
                    .$speaker.($designation ? ' <span style="font-weight: 400; color: #7a6e65;">('.$designation.')</span>' : '')
                    .'</div>' : '')
                .'</blockquote>';
        }, $content);

        // 4. Video & Audio Player: [video src="..." caption="..."]
        $content = preg_replace_callback('/\[video\s+([^\]]+)\]/i', function ($matches) {
            $attrs = static::parseAttributes($matches[1]);
            $rawSrc = static::sanitizeUrl($attrs['src'] ?? '');
            $caption = htmlspecialchars($attrs['caption'] ?? '', ENT_QUOTES, 'UTF-8');

            if (empty($rawSrc)) {
                return '';
            }

            // Check if YouTube
            if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=))([\w-]{11})/i', $rawSrc, $yt)) {
                $ytId = $yt[1];
                $embedUrl = 'https://www.youtube.com/embed/'.$ytId;

                return '<div class="article-video-wrap" style="margin: 28px 0; clear: both;">'
                    .'<div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 10px; box-shadow: 0 6px 24px rgba(0,0,0,0.1);">'
                    .'<iframe src="'.$embedUrl.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position: absolute; top:0; left:0; width:100%; height:100%;"></iframe>'
                    .'</div>'
                    .($caption ? '<div style="font-size: 13px; color: #7a6e65; text-align: center; margin-top: 8px;">'.$caption.'</div>' : '')
                    .'</div>';
            }

            $src = htmlspecialchars($rawSrc, ENT_QUOTES, 'UTF-8');

            return '<div class="article-video-wrap" style="margin: 28px 0; clear: both; text-align: center;">'
                .'<video src="'.$src.'" controls playsinline style="width: 100%; max-height: 480px; border-radius: 10px; box-shadow: 0 6px 24px rgba(0,0,0,0.1);"></video>'
                .($caption ? '<div style="font-size: 13px; color: #7a6e65; margin-top: 8px;">'.$caption.'</div>' : '')
                .'</div>';
        }, $content);

        // 5. Interactive Poll Widget: [widget:opinion_poll ...] or [poll question="..." opt1="..." opt2="..." opt3="..."]
        $content = preg_replace_callback('/\[(?:widget:opinion_poll|poll)\s*([^\]]*)\]/i', function ($matches) {
            $attrs = static::parseAttributes($matches[1]);
            $question = htmlspecialchars($attrs['question'] ?? 'Should Ramghat Parikrama Path be designated 100% Eco-Zone?', ENT_QUOTES, 'UTF-8');

            // Collect all options dynamically (opt1, opt2, opt3, opt4, ...) or from 'options' attribute
            $options = [];
            foreach ($attrs as $k => $v) {
                if (preg_match('/^opt\d+$/i', $k) && trim($v) !== '') {
                    $options[] = htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
                }
            }

            if (empty($options) && ! empty($attrs['options'])) {
                $rawOpts = explode('|', $attrs['options']);
                if (count($rawOpts) === 1) {
                    $rawOpts = explode(',', $attrs['options']);
                }
                foreach ($rawOpts as $ro) {
                    if (trim($ro) !== '') {
                        $options[] = htmlspecialchars(trim($ro), ENT_QUOTES, 'UTF-8');
                    }
                }
            }

            if (empty($options)) {
                $options = [
                    'हाँ, पूर्ण समर्थन (Yes, full support)',
                    'नहीं, अन्य व्यवस्था जरूरी (Alternative needed)',
                ];
            }

            $pollId = 'poll_'.substr(md5($question), 0, 8);
            $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            $encodedOptions = htmlspecialchars(json_encode($options), ENT_QUOTES, 'UTF-8');

            $buttonsHtml = '';
            foreach ($options as $idx => $opt) {
                $letter = $letters[$idx] ?? chr(65 + $idx);
                $buttonsHtml .= '<button type="button" class="poll-option-btn" onclick="submitReaderPollVote(\''.$pollId.'\', '.$idx.')" style="display: flex; align-items: center; gap: 12px; width: 100%; text-align: left; background: #fffdfc; border: 1.5px solid rgba(185,133,80,0.3); padding: 13px 18px; border-radius: 10px; font-size: 14px; font-family: var(--sans, sans-serif); color: #2b1a12; cursor: pointer; transition: all .2s ease; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">'
                    .'<span style="display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: rgba(185,133,80,0.12); color: #8f1719; font-weight: 800; font-size: 12px; flex-shrink: 0;">'.$letter.'</span>'
                    .'<span style="font-weight: 500; flex: 1;">'.$opt.'</span>'
                    .'<span style="font-size: 13px; color: #b98550; opacity: 0; transition: opacity .2s ease;" class="poll-btn-arrow">&rarr;</span>'
                    .'</button>';
            }

            return '<div id="'.$pollId.'" class="article-widget opinion-poll-card" data-options="'.$encodedOptions.'" style="margin: 36px 0; background: linear-gradient(180deg, #ffffff 0%, #fdfbf7 100%); border: 1.5px solid rgba(185,133,80,0.4); border-radius: 16px; padding: 26px 28px; box-shadow: 0 6px 24px rgba(43,26,18,0.06); clear: both;">'
                .'<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">'
                .'<span style="font-size: 11px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: var(--crimson, #8f1719); display: inline-flex; align-items: center; gap: 6px;">'
                .'<span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981; display: inline-block;"></span>'
                .'READER OPINION POLL · जनमत सर्वेक्षण'
                .'</span>'
                .'<span class="poll-status-tag" style="font-size: 11px; font-weight: 700; color: #b98550; background: rgba(185,133,80,0.1); padding: 3px 10px; border-radius: 9999px;">लाइव जनमत</span>'
                .'</div>'
                .'<h4 style="font-family: var(--dev, serif); font-size: 20px; line-height: 1.4; color: var(--deep, #16100d); margin: 0 0 18px; font-weight: 700;">'.$question.'</h4>'
                // Voting view
                .'<div id="'.$pollId.'_voting" class="poll-voting-pane" style="display: flex; flex-direction: column; gap: 10px;">'
                .$buttonsHtml
                .'</div>'
                // Results view (hidden initially)
                .'<div id="'.$pollId.'_results" class="poll-results-pane" style="display: none;">'
                .'<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">'
                .'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>'
                .'<span>आपका मत सफलतापूर्वक दर्ज हो गया है! तत्पर परिणाम नीचे देखें:</span>'
                .'</div>'
                .'<div id="'.$pollId.'_bars" style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 16px;"></div>'
                .'<div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #7a6e65; border-top: 1px solid rgba(185,133,80,0.2); padding-top: 12px;">'
                .'<span id="'.$pollId.'_total_label">कुल मत: गणना जारी...</span>'
                .'<button type="button" onclick="resetReaderPollVote(\''.$pollId.'\')" style="background: none; border: none; color: #8f1719; text-decoration: underline; font-size: 11.5px; font-weight: 700; cursor: pointer; padding: 0;">मत बदलें (Change vote)</button>'
                .'</div>'
                .'</div>'
                // Inline Script for Poll Interaction & LocalStorage Persistence
                .'<script>
                (function() {
                  const pId = "'.$pollId.'";
                  function initPoll() {
                    const voted = localStorage.getItem("voted_" + pId);
                    if (voted !== null) {
                      renderPollResults(pId, parseInt(voted, 10));
                    }
                  }
                  if (document.readyState === "loading") {
                    document.addEventListener("DOMContentLoaded", initPoll);
                  } else {
                    initPoll();
                  }
                })();
                if (typeof window.submitReaderPollVote === "undefined") {
                  window.submitReaderPollVote = function(pId, chosenIdx) {
                    localStorage.setItem("voted_" + pId, chosenIdx);
                    window.renderPollResults(pId, chosenIdx);
                  };
                  window.resetReaderPollVote = function(pId) {
                    localStorage.removeItem("voted_" + pId);
                    const card = document.getElementById(pId);
                    if (!card) return;
                    card.querySelector(".poll-voting-pane").style.display = "flex";
                    card.querySelector(".poll-results-pane").style.display = "none";
                  };
                  window.renderPollResults = function(pId, chosenIdx) {
                    const card = document.getElementById(pId);
                    if (!card) return;
                    const options = JSON.parse(card.getAttribute("data-options") || "[]");
                    const votingPane = card.querySelector(".poll-voting-pane");
                    const resultsPane = card.querySelector(".poll-results-pane");
                    const barsContainer = document.getElementById(pId + "_bars");
                    const totalLabel = document.getElementById(pId + "_total_label");

                    votingPane.style.display = "none";
                    resultsPane.style.display = "block";

                    // Deterministic seed count based on poll ID string hash
                    let hash = 0;
                    for (let i = 0; i < pId.length; i++) hash = ((hash << 5) - hash) + pId.charCodeAt(i);
                    hash = Math.abs(hash);

                    // Base simulated counts
                    const counts = options.map((opt, i) => {
                      const base = ((hash + (i * 37)) % 80) + 25;
                      return i === chosenIdx ? base + 1 : base;
                    });
                    const total = counts.reduce((a, b) => a + b, 0);

                    barsContainer.innerHTML = "";
                    const escPollHtml = (str) => String(str)
                      .replace(/&/g, "&amp;")
                      .replace(/</g, "&lt;")
                      .replace(/>/g, "&gt;")
                      .replace(/"/g, "&quot;")
                      .replace(/\x27/g, "&#039;");

                    options.forEach((opt, idx) => {
                      const count = counts[idx];
                      const pct = Math.round((count / total) * 100);
                      const isUserChoice = idx === chosenIdx;

                      const row = document.createElement("div");
                      row.style.display = "flex";
                      row.style.flexDirection = "column";
                      row.style.gap = "6px";

                      row.innerHTML = `
                        <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13.5px; font-weight: ${isUserChoice ? "700" : "500"}; color: #2b1a12;">
                          <div style="display: flex; align-items: center; gap: 8px;">
                            ${isUserChoice ? "<span style=\'color: #059669; font-weight: 800;\'>✓</span>" : ""}
                            <span>${escPollHtml(opt)}</span>
                            ${isUserChoice ? "<span style=\'font-size: 10px; background: #059669; color: #fff; padding: 1px 7px; border-radius: 9999px; font-weight: 700;\'>आपका मत</span>" : ""}
                          </div>
                          <span style="font-weight: 800; color: ${isUserChoice ? "#8f1719" : "#b98550"};">${pct}% (${count})</span>
                        </div>
                        <div style="height: 10px; background: #f1ede6; border-radius: 9999px; overflow: hidden; position: relative;">
                          <div style="width: 0%; height: 100%; border-radius: 9999px; background: ${isUserChoice ? "linear-gradient(90deg, #d95d0f, #8f1719)" : "linear-gradient(90deg, #b98550, #7a4b29)"}; transition: width .8s cubic-bezier(0.4, 0, 0.2, 1);"></div>
                        </div>
                      `;
                      barsContainer.appendChild(row);

                      setTimeout(() => {
                        const bar = row.querySelector("div > div");
                        if (bar) bar.style.width = pct + "%";
                      }, 50);
                    });

                    if (totalLabel) {
                      totalLabel.innerText = "कुल मत: " + total.toLocaleString("en-IN") + " · लाइव परिणाम";
                    }
                  };
                }
                </script>'
                .'</div>';
        }, $content);

        // 6. River Mandakini Gauge Widget: [widget:mandakini_gauge]
        $content = preg_replace(
            '/\[widget:mandakini_gauge\]/i',
            '<div class="article-widget river-gauge-card" style="margin: 32px 0; background: linear-gradient(135deg, #140e0b, #2b231d); color: #ffffff; border-radius: 12px; padding: 24px 28px; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">'
            .'<div style="font-size: 11px; font-weight: 800; letter-spacing: .15em; text-transform: uppercase; color: #f4a900; margin-bottom: 6px;">RAMGHAT LIVE STATUS</div>'
            .'<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">'
            .'<div><div style="font-size: 26px; font-weight: 700; color: #ffffff;">28°C</div><span style="font-size: 12px; color: rgba(255,255,255,0.7);">Sunny · Pleasant River Breeze</span></div>'
            .'<div><div style="font-size: 26px; font-weight: 700; color: #4ade80;">124.5 m</div><span style="font-size: 12px; color: rgba(255,255,255,0.7);">Mandakini Water Level (Normal/Safe)</span></div>'
            .'<div><a href="/chitrakoot" style="display: inline-block; background: #f4a900; color: #16100d; font-size: 12px; font-weight: 700; padding: 8px 16px; border-radius: 9999px; text-decoration: none;">View Ramghat Cam</a></div>'
            .'</div>'
            .'</div>',
            $content
        );

        // 7. Donation / Membership Card: [campaign type="..."] or [widget:membership_cta]
        $content = preg_replace_callback('/\[(?:campaign|widget:membership_cta)\s*([^\]]*)\]/i', function ($matches) {
            $attrs = static::parseAttributes($matches[1] ?? '');
            $title = htmlspecialchars($attrs['title'] ?? 'Become a Monthly Patron Member', ENT_QUOTES, 'UTF-8');
            $amount = htmlspecialchars($attrs['amount'] ?? '₹400/month', ENT_QUOTES, 'UTF-8');

            return '<div class="article-widget campaign-card" style="margin: 36px 0; background: #fff8f0; border: 2px solid var(--sand, #b98550); border-radius: 14px; padding: 26px 30px; text-align: center; box-shadow: 0 6px 20px rgba(185,133,80,0.12);">'
                .'<div style="font-size: 11px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: var(--crimson, #8f1719); margin-bottom: 6px;">SHRINE SUPPORT INITIATIVE</div>'
                .'<h3 style="font-family: var(--dev, serif); font-size: 22px; color: var(--deep, #16100d); margin-bottom: 8px;">'.$title.'</h3>'
                .'<p style="font-size: 14px; color: #593a28; margin-bottom: 18px;">Join our community of devotees sustaining Gaushala, Sanskrit education, and Mandakini protection.</p>'
                .'<a href="/donate" style="display: inline-flex; align-items: center; gap: 8px; background: var(--crimson, #8f1719); color: #ffffff; padding: 12px 28px; border-radius: 9999px; font-weight: 700; font-size: 14px; text-decoration: none; box-shadow: 0 4px 14px rgba(143,23,25,0.3);">'
                .'Contribute '.$amount.' &rarr;'
                .'</a>'
                .'</div>';
        }, $content);

        return $content;
    }

    /**
     * Helper to parse key="value" or key='value' strings from shortcode tags.
     *
     * @return array<string, string>
     */
    protected static function parseAttributes(string $attrString): array
    {
        $attributes = [];
        $cleanString = stripslashes(html_entity_decode($attrString, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        if (preg_match_all('/([\w\-:]+)\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s"\'\]]+))/u', $cleanString, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $key = strtolower($match[1] ?? '');
                if ($key === '') {
                    continue;
                }

                $val = '';
                if (isset($match[2])) {
                    $val = $match[2];
                } elseif (isset($match[3])) {
                    $val = $match[3];
                } elseif (isset($match[4])) {
                    $val = $match[4];
                }

                $attributes[$key] = $val;
            }
        }

        return $attributes;
    }

    /**
     * Validate and sanitize a media URL to allow only safe HTTP(S) or root-relative paths.
     */
    protected static function sanitizeUrl(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }

        // Decode any obfuscated entities/whitespace before checking scheme
        $decoded = html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = preg_replace('/[\x00-\x20]+/', '', $decoded);

        if (preg_match('/^(?:https?:\/\/|\/(?!\/))/i', (string) $normalized)) {
            return $url;
        }

        return '';
    }
}
