<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ufuk</title>
  <link rel="stylesheet" href="https://stackedit.io/style.css" />
</head>

<body class="stackedit">
  <div class="stackedit__html"><h1 id="codeigniter-database-backup">Codeigniter Database Backup</h1>
<h2 id="gereksinimler">Gereksinimler</h2>
<ol>
<li>PHP 5.2+</li>
<li>CodeIgniter 2 veya üstü</li>
</ol>
<h2 id="kurulum">Kurulum</h2>
<h4 id="adım-1">Adım 1</h4>
<pre class=" language-php"><code class="prism  language-php"><span class="token variable">$tables</span> <span class="token operator">=</span> <span class="token variable">$this</span><span class="token operator">-</span><span class="token operator">&gt;</span><span class="token property">db</span><span class="token operator">-</span><span class="token operator">&gt;</span><span class="token function">list_tables</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span>  
  
<span class="token comment">// Tabloları ve viewleri listeleme  </span>
<span class="token keyword">foreach</span> <span class="token punctuation">(</span><span class="token variable">$tables</span> <span class="token keyword">as</span> <span class="token variable">$table</span><span class="token punctuation">)</span><span class="token punctuation">{</span>

	<span class="token comment">// DB view olan dosyları bulma</span>
    <span class="token variable">$konum</span> <span class="token operator">=</span> <span class="token function">strpos</span><span class="token punctuation">(</span><span class="token variable">$table</span><span class="token punctuation">,</span> <span class="token string">'_view'</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token keyword">if</span><span class="token punctuation">(</span><span class="token variable">$konum</span> <span class="token operator">===</span> <span class="token boolean">false</span><span class="token punctuation">)</span> <span class="token variable">$data_table</span><span class="token punctuation">[</span><span class="token punctuation">]</span><span class="token operator">=</span> <span class="token variable">$table</span><span class="token punctuation">;</span>
    <span class="token keyword">else</span> <span class="token variable">$data_view</span><span class="token punctuation">[</span><span class="token punctuation">]</span><span class="token operator">=</span> <span class="token variable">$table</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
</code></pre>
<blockquote>
<p><strong>Not:</strong> İki farklı dizini tek bir değişkene atamak için array_merge($array1, $array2) kullanıyoruz. Bunun nedeni database’i import ederken önce tabloları eklemesi ve eklenen tablolara göre oluşturduğumuz view tablolarını eklemesi sağlamak. Aksi taktirde import işlemi sırasında hata alabilirsiniz.</p>
</blockquote>
<h4 id="adım-2">Adım 2</h4>
<hr>
<pre class=" language-php"><code class="prism  language-php"><span class="token comment">// Yedekleme işlemini başlatıyoruz  </span>
<span class="token variable">$this</span><span class="token operator">-</span><span class="token operator">&gt;</span><span class="token property">load</span><span class="token operator">-</span><span class="token operator">&gt;</span><span class="token function">dbutil</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span>  
<span class="token variable">$prefs</span> <span class="token operator">=</span> <span class="token keyword">array</span><span class="token punctuation">(</span>  
    <span class="token string">'tables'</span> <span class="token operator">=</span><span class="token operator">&gt;</span> <span class="token function">array_merge</span><span class="token punctuation">(</span><span class="token variable">$data_table</span><span class="token punctuation">,</span> <span class="token variable">$data_view</span><span class="token punctuation">)</span><span class="token punctuation">,</span>    <span class="token comment">// Yedeklenecek tablo dizisi  </span>
    <span class="token string">'ignore'</span> <span class="token operator">=</span><span class="token operator">&gt;</span> <span class="token keyword">array</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">,</span>                                 <span class="token comment">// Yedeklemeden çıkarılacak tabloların listesi  </span>
    <span class="token string">'format'</span> <span class="token operator">=</span><span class="token operator">&gt;</span> <span class="token string">'zip'</span><span class="token punctuation">,</span>                                   <span class="token comment">// Format türleri gzip, zip, txt  </span>
    <span class="token string">'filename'</span> <span class="token operator">=</span><span class="token operator">&gt;</span> <span class="token string">'backup.sql'</span><span class="token punctuation">,</span>                          <span class="token comment">// Dosya adı - (Dosya adına sadece zip formatında yedekleme yapılırsa ihtiyaç vardır)  </span>
    <span class="token string">'add_drop'</span> <span class="token operator">=</span><span class="token operator">&gt;</span> <span class="token constant">TRUE</span><span class="token punctuation">,</span>                                  <span class="token comment">// DROP TABLE ifadelerinin yedekleme dosyasına eklenip eklenmeyeceği  </span>
    <span class="token string">'add_insert'</span> <span class="token operator">=</span><span class="token operator">&gt;</span> <span class="token constant">TRUE</span><span class="token punctuation">,</span>                                <span class="token comment">// INSERT verilerini yedekleme dosyasına eklemek ister  </span>
    <span class="token string">'newline'</span> <span class="token operator">=</span><span class="token operator">&gt;</span> <span class="token string">"\n"</span> 								     <span class="token comment">// Yedek dosyada kullanılan yeni satır karakteri  </span>
<span class="token punctuation">)</span><span class="token punctuation">;</span>  
<span class="token variable">$backup</span> <span class="token operator">=</span> <span class="token variable">$this</span><span class="token operator">-</span><span class="token operator">&gt;</span><span class="token property">dbutil</span><span class="token operator">-</span><span class="token operator">&gt;</span><span class="token function">backup</span><span class="token punctuation">(</span><span class="token variable">$prefs</span><span class="token punctuation">)</span><span class="token punctuation">;</span>  
<span class="token variable">$db_name</span> <span class="token operator">=</span> <span class="token string">'backup-'</span><span class="token punctuation">.</span><span class="token function">date</span><span class="token punctuation">(</span><span class="token string">"d-m-Y-H-i-s"</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token string">'.zip'</span><span class="token punctuation">;</span>  
<span class="token variable">$save</span> <span class="token operator">=</span> <span class="token string">'backup/'</span><span class="token punctuation">.</span><span class="token variable">$db_name</span><span class="token punctuation">;</span>  
<span class="token function">write_file</span><span class="token punctuation">(</span><span class="token variable">$save</span><span class="token punctuation">,</span> <span class="token variable">$backup</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</code></pre>
<hr>
<blockquote>
<p>Eğer dosyanın yedeklemeden sonra otomatik inmesini istiyorsak</p>
</blockquote>
<pre class=" language-php"><code class="prism  language-php"><span class="token function">force_download</span><span class="token punctuation">(</span><span class="token variable">$db_name</span><span class="token punctuation">,</span> <span class="token variable">$backup</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</code></pre>
</div>
</body>

</html>
