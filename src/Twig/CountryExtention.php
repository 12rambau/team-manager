<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Locale;
use Twig\TwigFilter;

class CountryExtention extends AbstractExtension
{
    private $defaultLocale;

    public function __construct($defaultLocale){
        $this->defaultLocale = $defaultLocale;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('alpha2', [$this, 'getAlpha2code']),
            new TwigFilter('language', [$this, 'getLanguageName'])
        ];
    }

    /**
     * Transform a locale variable in a Alpha2 country code 
     * 
     * @param string $locale the locale to be transformed in RFC 4646
     * 
     * @return string|null the alpha2 code in ISO 639-1
     */
    public function getAlpha2code($locale){

        return Locale::getRegion($locale);
    }

    /**
     * Transform the locale variable in a language name displayed in the current locale
     * 
     * @param string $locale the locale to be transformed in RFC 4646
     * @param string|null $inLocale the target locale to display the language name
     * 
     * @return string|null the language name in the target locale or in the app.locale
     */
    public function getLanguageName($locale, $inLocale = null)
    {
        if (!$inLocale)
            $inLocale = $this->defaultLocale;

        return Locale::getDisplayLanguage($locale, $inLocale); 
    }
}
