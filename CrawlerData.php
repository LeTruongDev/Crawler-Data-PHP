<?php

require_once('simple_html_dom.php');

$url = 'https://dantri.com.vn/giao-duc-huong-nghiep/vu-lo-de-thi-sinh-8-thi-sinh-duoc-mom-de-can-xu-ly-the-nao-20230620004656478.htm';

$html = file_get_html($url);


function crawlData($data)
{
    $articles = [];
    foreach ($data as $item) {
        $title = $item->find('.article-thumb a img', 0)->alt;
        $thumbnail = $item->find('.article-thumb a img', 0)->{"data-src"};
        $url = $item->find('.article-thumb a', 0)->href;
        $url = 'https://dantri.com.vn' . $url;
    
        $isDuplicate = false;
        foreach ($articles as $key => $article) {
            if ($article['url'] === $url) {
                $isDuplicate = true;
                break;
            }
        }
    
        if (!$isDuplicate) {
            $titleLength = strlen($title);
            $thumbnailLength = strlen($thumbnail);
            $urlLength = strlen($url);
    
            $articles[] = [
                'title' => "string($titleLength) \"$title\"",
                'thumbnail_image' => "string($thumbnailLength) \"$thumbnail\"",
                'url' => "string($urlLength) \"$url\"",
            ];
        }
    
        if (count($articles) === 3) {
            break;
        }
    }
    $outputArray = [];
    foreach ($articles as $article) {
        $outputArray[] = [
            '"title"' => $article['title'],
            '"thumbnail_image"' => $article['thumbnail_image'],
            '"url"' => $article['url'],
        ];
    }
    return $outputArray;
}

$readMany = $html->find('.article-lot article');
$relatedNews = $html->find('.article-related article');

$ofInterest = $html->find('.article-care article');



$resultReadMany = crawlData($readMany);
$resultRelatedNews = crawlData($relatedNews);
$resultOfInterest = crawlData($ofInterest);

$combinedData = [
    'readMany' => $resultReadMany,
    'relatedNews' => $resultRelatedNews,
    'ofInterest' => $resultOfInterest,
];

print_r($combinedData);

$html->clear();
unset($html);

?>
