<?php
class Application_Plugin_LanguageSetup extends Zend_Controller_Plugin_Abstract {
 
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $lang = $request->getParam('lang');
        $config = new Zend_Config_Ini('../application/configs/translate.ini', 'production');
        if (($lang!="en")&&($lang!="ru")&&($lang!="uk"))
            $lang = $config->locale->default;
        try {
            $locale = new Zend_Locale(Zend_Locale::BROWSER);
        } catch (Exception $e) {
            $locale = new Zend_Locale();
        }
        
        $loc = $config->translate->options->locales->$lang;
        $locale->setLocale($lang);
        Zend_Registry::set('Zend_Locale', $loc);
 
        $translate = new Zend_Translate(
                            array(
                                'adapter' => $config->translate->adapter,
                                'content' => APPLICATION_PATH . '/languages/' .$config->translate->date. $lang . '/obmen.mo',
                                'locale' => $lang
                            )
            );
        Zend_Registry::set('Zend_Translate', $translate);
        $save_lang = ($lang == 'uk')?'ua':$lang;
        Zend_Registry::set('uds_lang', $save_lang);
//        Zend_Registry::set('uds_lang_id', $lang_id);
//        $_SESSION['lang_code'] = $lang;
//        $_SESSION['lang_id'] = $lang_id;
    }
 
}

?>
