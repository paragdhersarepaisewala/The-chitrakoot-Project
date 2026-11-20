{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>{{ config('app.name', 'Shri Kamadgiri Peetham Sewa Trust') }}</title>
    <link>{{ url('/') }}</link>
    <description>श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम — पावन विचार, उत्सव, सेवा प्रकल्प एवं समाचार</description>
    <language>hi-IN</language>
    <atom:link href="{{ url('/feed.xml') }}" rel="self" type="application/rss+xml" />
    <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
    @foreach ($posts as $post)
      <item>
        <title><![CDATA[{{ $post->title_hi ?: $post->title_en }}]]></title>
        <link>{{ url('/blog/' . $post->slug) }}</link>
        <guid isPermaLink="true">{{ url('/blog/' . $post->slug) }}</guid>
        <pubDate>{{ ($post->published_at ?: $post->created_at)->toRfc2822String() }}</pubDate>
        <description><![CDATA[{{ Str::limit(strip_tags($post->excerpt ?: ($post->content_hi ?: $post->content_en)), 300) }}]]></description>
        @if (!empty($post->featured_image))
          <enclosure url="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : url($post->featured_image) }}" type="image/jpeg" length="0" />
        @endif
      </item>
    @endforeach
  </channel>
</rss>
