<?php
// Дан текст новости
// Необходимо обрезать его до 29 слов с добавлением многоточия.
// Форматирование необходимо сохранить.

$text = <<<TXT
<p class="big">
	Год основания:<b>1589 г.</b> Волгоград отмечает день города в <b>2-е воскресенье сентября</b>. <br>В <b>2023 году</b> эта дата - <b>10 сентября</b>.
</p>
<p class="float">
	<img src="https://www.calend.ru/img/content_events/i0/961.jpg" alt="Волгоград" width="300" height="200" itemprop="image">
	<span class="caption gray">Скульптура «Родина-мать зовет!» входит в число семи чудес России (Фото: Art Konovalov, по лицензии shutterstock.com)</span>
</p>
<p> 
	<i><b>Великая Отечественная война в истории города</b></i></p><p><i>Важнейшей операцией Советской Армии в Великой Отечественной войне стала <a href="https://www.calend.ru/holidays/0/0/1869/">Сталинградская битва</a> (17.07.1942 - 02.02.1943). Целью боевых действий советских войск являлись оборона  Сталинграда и разгром действовавшей на сталинградском направлении группировки противника. Победа советских войск в Сталинградской битве имела решающее значение для победы Советского Союза в Великой Отечественной войне.</i>
</p>
TXT;

function truncateNodes(DOMNode $node, &$wordCount, $wordLimit, &$truncated)
{
    foreach (iterator_to_array($node->childNodes) as $child) {
        if ($truncated) {
            $node->removeChild($child);
            continue;
        }

        if ($child->nodeType === XML_TEXT_NODE) {
            $words = preg_split('/\s+/', trim($child->nodeValue));
            //var_dump($words);
            $currentWordCount = 0;
            foreach ($words as $word) {
                //сюда можно добавить еще сочетаний спец.символов которые могут стоять в одиночестве
                if (!in_array($word, ['.', ',', ' ', ':', ';', '!', '?', '-', '', '/', '\\', '\'', '@', '"'])) {
                    $currentWordCount += 1;


                }
            }
            if ($wordCount + $currentWordCount > $wordLimit) {
                $remainingWords = $wordLimit - $wordCount;
                $child->nodeValue = implode(' ', array_slice($words, 0, $remainingWords)) . '...';
                $truncated = true;
            }

            $wordCount += $currentWordCount;
        } elseif ($child->nodeType === XML_ELEMENT_NODE) {
            truncateNodes($child, $wordCount, $wordLimit, $truncated);
        }
    }
}

function truncateHtmlText($html, $wordLimit)
{
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

    $xpath = new DOMXPath($dom);
    $body = $xpath->query('//html')->item(0);

    $wordCount = 0;
    $truncated = false;

    truncateNodes($body, $wordCount, $wordLimit, $truncated);

    return $dom->saveHTML($body);
}

$result = truncateHtmlText($text, 29);


$result = preg_replace('~<body>|</body>|<html>|</html>~', '', $result);

echo "
<table style='width:70%; border: 1px solid black;'>
        <thead>
            <tr>
                <th style='width: 50%'><h3>Исходный текст:</h3></th>
                <th><h3>Результат обработки:</h3></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{$text}</td>
                <td>{$result}</td>
            </tr>
        </tbody>
</table>";
