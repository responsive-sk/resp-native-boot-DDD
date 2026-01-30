<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

final readonly class Slug
{
    private string $value;

    public function __construct(string $slug)
    {
        $this->value = self::normalize($slug);
    }

    public static function fromString(string $slug): self
    {
        return new self($slug);
    }

    public function toString(): string
    {
        return $this->value;
    }

    private static function normalize(string $slug): string
    {
        // 1. Odstrániť HTML tagy
        $slug = strip_tags($slug);

        // 2. Konvertovať na lowercase
        $slug = mb_strtolower($slug, 'UTF-8');

        // 3. NAJPRV transliterácia slovenských a českých znakov
        $slug = self::transliterate($slug);

        // 4. Až TERAZ odstrániť diakritiku pomocou iconv (zvyšok)
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);

        // 5. Nahradiť všetky znaky okrem a-z, 0-9 a - pomlčkou
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        // 6. Odstrániť začiatok a koniec pomlčiek
        $slug = trim($slug, '-');

        // 7. Zabezpečiť, aby nebol prázdny
        if ($slug === '') {
            throw new \InvalidArgumentException('Slug nemôže byť prázdny po normalizácii');
        }

        // 8. Obmedziť dĺžku
        if (mb_strlen($slug) > 100) {
            $slug = mb_substr($slug, 0, 100);
            $slug = rtrim($slug, '-');
        }

        return $slug;
    }

    private static function transliterate(string $text): string
    {
        $replacements = [
            // Slovenské
            'á' => 'a', 'ä' => 'a', 'č' => 'c', 'ď' => 'd',
            'é' => 'e', 'ě' => 'e', 'í' => 'i', 'ĺ' => 'l',
            'ľ' => 'l', 'ň' => 'n', 'ó' => 'o', 'ô' => 'o',
            'ŕ' => 'r', 'š' => 's', 'ť' => 't', 'ú' => 'u',
            'ý' => 'y', 'ž' => 'z',

            // Veľké slovenské
            'Á' => 'a', 'Ä' => 'a', 'Č' => 'c', 'Ď' => 'd',
            'É' => 'e', 'Ě' => 'e', 'Í' => 'i', 'Ĺ' => 'l',
            'Ľ' => 'l', 'Ň' => 'n', 'Ó' => 'o', 'Ô' => 'o',
            'Ŕ' => 'r', 'Š' => 's', 'Ť' => 't', 'Ú' => 'u',
            'Ý' => 'y', 'Ž' => 'z',

            // České
            'ř' => 'r', 'Ř' => 'r', 'ů' => 'u', 'Ů' => 'u',
            'ď' => 'd', 'Ď' => 'd', 'ť' => 't', 'Ť' => 't',
            'ň' => 'n', 'Ň' => 'n',

            // Polské (pre kompatibilitu)
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l',
            'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',
            'Ą' => 'a', 'Ć' => 'c', 'Ę' => 'e', 'Ł' => 'l',
            'Ń' => 'n', 'Ó' => 'o', 'Ś' => 's', 'Ź' => 'z',
            'Ż' => 'z',
        ];

        return strtr($text, $replacements);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
