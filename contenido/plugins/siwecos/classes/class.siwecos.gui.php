<?php

/**
 *
 * @package    Plugin
 * @subpackage SIWECOS
 * @author     Fulai Zhang <fulai.zhang@4fb.de>
 * @copyright  four for business AG
 * @link       http://www.4fb.de
 */

// assert CONTENIDO framework
defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

/**
 * Page to be displayed in the left bottom frame.
 * It provides a navigation for all connections defined for the current client and the
 * current language.
 *
 * @author Fulai Zhang <fulai.zhang@4fb.de>
 */
class SIWECOSLeftBottomPage extends cGuiPage
{
    /**
     * id of the SIWECOS
     *
     * @var int
     */
    protected $idsiwecos;

    /**
     * SIWECOSLeftBottomPage constructor.
     *
     * @throws SIWECOSException
     * @throws cDbException
     * @throws cException
     * @throws cInvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct('left_bottom', 'siwecos');
        $this->_getMenu();
    }

    /**
     * Get menu with all forms of current client in current language.
     *
     * @return string
     * @throws SIWECOSException
     * @throws cDbException
     * @throws cException
     * @throws cInvalidArgumentException
     */
    private function _getMenu()
    {
        global $area;

        $cfg    = cRegistry::getConfig();
        $client = cRegistry::getClientId();
        $lang   = cRegistry::getLanguageId();

        // get all forms of current client in current language
        $forms = SIWECOSCollection::getByClientAndLang($client, $lang);
        if (false === $forms) {
            return '<!-- no forms for current client/language -->';
        }

        // create menu
        $oPage = new cGuiPage("siwecos_menu", "siwecos");
        $menu  = new cGuiMenu();
        foreach ($forms as $form) {
            $idsiwecos = $form['idsiwecos'];
            $domain    = $form['domain'];

            $menu->setTitle($idsiwecos, $domain);

            // create link to show/edit the form
            $link = new cHTMLLink();
            $link->setMultiLink($area, '', $area, SIWECOSRightBottomPage::SHOW_FORM);
            $link->setCustom('idsiwecos', $idsiwecos);
            $link->setAttribute('title', 'idsiwecos: ' . $idsiwecos);
            $menu->setLink($idsiwecos, $link);

            $deleteForm = 'DELETE_FORM';
            // create link to delete the form
            if (cRegistry::getPerm()->have_perm_area_action('form', SIWECOSRightBottomPage::DELETE_FORM)) {
                $link = new cHTMLLink();
                $link->setMultiLink(
                    $area,
                    SIWECOSRightBottomPage::DELETE_FORM,
                    $area,
                    SIWECOSRightBottomPage::DELETE_FORM
                );
                $link->setCustom('idsiwecos', $idsiwecos);
                $link->setClass('SIWECOS-icon-delete-form');
                $link->setAlt($deleteForm);
                $link->setContent(
                    '<img src="' . $cfg['path']['images'] . 'delete.gif" title="' . $deleteForm . '" alt="'
                    . $deleteForm . '">'
                );
                // $menu->setLink($idform, $link);
                $menu->setActions($idsiwecos, 'delete', $link);
            } else {
                $menu->setActions(
                    $idsiwecos,
                    'delete',
                    '<img src="' . $cfg['path']['images'] . 'delete_inact.gif" title="' . $deleteForm . '" alt="'
                    . $deleteForm . '">'
                );
            }
        }

        $oPage->setContent($menu);

        return $oPage->render();
    }
}

/**
 * Page for area "form" to be displayed in the right bottom frame.
 *
 * @author Fulai Zhang <fulai.zhang@4fb.de>
 */
class SIWECOSRightBottomPage extends cGuiPage
{
    /**
     * Action constant.
     *
     * @var string
     */
    const METHODE_GET       = 'GET';
    const METHODE_POST      = 'POST';
    const SHOW_FORM         = 'siwecos_show';
    const VERIFICATION_FORM = 'siwecos_verification';
    const ADD_FORM          = 'siwecos_add';
    const SCAN_FORM         = 'siwecos_scan';
    const STORE_FORM        = 'siwecos_store';
    const DELETE_FORM       = 'siwecos_delete';

    private $_SIWECOSForm;

    /**
     * Creates and aggregates a model for a collection of SIWECOS forms
     * and another for a single SIWECOS form.
     *
     * If an ID for an item is given this is loaded from database
     * and its values are stored in the appropriate model.
     *
     * @throws SIWECOSException if form could not be loaded
     * @throws cDbException
     * @throws cException
     */
    public function __construct()
    {
        /**
         *
         * @param string $action to be performed
         */
        global $action;

        /**
         * id of the SIWECOS
         *
         * @param int $idsiwecos id of form to be edited
         */
        global $idsiwecos;

        parent::__construct('right_bottom', 'siwecos');
        $this->addStyle('siwecos.css');
        $this->addStyle('template.css');
        $this->addScript('jquery.AshAlom.gaugeMeter-2.0.0.min.js');

        $this->_SIWECOSForm = new SIWECOS();

        // load models
        $idsiwecos = cSecurity::toInteger($idsiwecos);
        if (0 < $idsiwecos) {
            if (false === $this->_SIWECOSForm->loadByPrimaryKey($idsiwecos)) {
                $msg = i18n('ERR_LOAD_ENTITY', 'siwecos');
                throw new SIWECOSException($msg);
            }
        }

        // dispatch action
        try {
            $this->_dispatch($action);
        } catch (SIWECOSException $e) {
            $cGuiNotification = new cGuiNotification();
            $notification     = $cGuiNotification->returnNotification(cGuiNotification::LEVEL_ERROR, $e->getMessage());
            $this->set('s', 'notification', $notification);
            $this->set('s', 'content', '');
        }
    }

    /**
     * Dispatches the given action.
     *
     * @param string $action to be executed
     *
     * @throws CurlException
     * @throws SIWECOSException if the given action is unknown
     * @throws cDbException
     * @throws cException
     */
    private function _dispatch($action)
    {
        global $area;

        $cGuiNotification = new cGuiNotification();

        // check for permission
        if (!cRegistry::getPerm()->have_perm_area_action($area, $action)) {
            throw new SIWECOSException(i18n('ERR_PERMISSION_DENIED', 'siwecos'));
        }

        $notification = $content = '';
        if (null === $action) {
            $notification = $cGuiNotification->returnNotification(
                cGuiNotification::LEVEL_OK,
                i18n('MSG_SELECT_DOMAIN', 'siwecos')
            );
        } else {
            // dispatch actions
            switch ($action) {
                case self::SHOW_FORM:
                    try {
                        $content = $this->_showForm();
                    } catch (Exception $e) {
                        $notification = SIWECOS::notifyException($e);
                    }
                    break;

                case self::VERIFICATION_FORM:
                    try {
                        $this->_startVerification();
                    } catch (Exception $e) {
                        $notification = SIWECOS::notifyException($e);
                    };
                    $content = $this->_showForm();
                    break;

                case self::SCAN_FORM:
                    try {
                        $this->_scanForm();
                        $notification = $cGuiNotification->returnNotification(cGuiNotification::LEVEL_OK, $notification);
                    } catch (Exception $e) {
                        $notification = SIWECOS::notifyException($e);
                    }
                    $content = $this->_showForm();
                    break;

                case self::STORE_FORM:
                    try {
                        $this->_validation();
                        $this->_storeForm();
                    } catch (Exception $e) {
                        $notification = SIWECOS::notifyException($e);
                    }
                    $this->setReload();
                    // reload right_top after saving of form
                    $idsiwecos  = $this->_SIWECOSForm->get('idsiwecos');
                    $formAction = new cHTMLLink();
                    $formAction->setCLink($area, 3, self::SHOW_FORM);
                    $formAction->setCustom('idsiwecos', $idsiwecos);
                    $url = $formAction->getHref();
                    $this->addScript(
                        "<script type=\"text/javascript\">
                            Con.getFrame('right_top').location.href = '$url';
                        </script>"
                    );
                    $content = $this->_showForm();
                    break;

                case self::DELETE_FORM:
                    try {
                        $this->_deleteForm();
                        $notification = $cGuiNotification->returnNotification(
                            cGuiNotification::LEVEL_OK,
                            i18n('MSG_DELETED_ENTITY', 'siwecos')
                        );
                        $content      = '';
                        $this->setReload();
                    } catch (Exception $e) {
                        $notification = SIWECOS::notifyException($e);
                    }
                    break;

                default:
                    $msg = i18n('ERR_UNKNOWN_ACTION', 'siwecos');
                    throw new SIWECOSException($msg);
            }
        }

        $this->set('s', 'notification', $notification);
        $this->set('s', 'content', $content);
    }

    /**
     * Build and return form for SIWECOS forms.
     *
     * @return string
     * @throws CurlException
     * @throws SIWECOSException
     * @throws cDbException
     * @throws cException
     */
    private function _showForm()
    {
        global $area, $auth, $belang;
        $cfg       = cRegistry::getConfig();
        $idsiwecos = $this->_SIWECOSForm->get('idsiwecos');

        // get form action
        $formAction = '';
        if (cRegistry::getPerm()->have_perm_area_action('form', self::STORE_FORM)) {
            $formAction = new cHTMLLink();
            $formAction->setCLink($area, 4, self::STORE_FORM);
            $formAction->setCustom('idsiwecos', $idsiwecos);
            $formAction = $formAction->getHref();
        }

        if (empty($idsiwecos)) {
            $idsiwecos   = null;
            $domain      = empty($_POST['domain']) ? '' : $_POST['domain'];
            $domain      = parse_url($domain);
            $domain      = $domain['scheme'] . '://' . $domain['host'];
            $email       = empty($_POST['email']) ? '' : $_POST['email'];
            $password    = empty($_POST['password']) ? '' : $_POST['password'];
            $userToken   = '';
            $domainToken = '';
            $dangerLevel = 10;
            $author      = $auth->getUsername();
            $created     = '';
        } else {
            $domain      = $this->_SIWECOSForm->get('domain');
            $email       = $this->_SIWECOSForm->get('email');
            $password    = '';
            $userToken   = $this->_SIWECOSForm->get('userToken');
            $domainToken = $this->_SIWECOSForm->get('domainToken');
            $dangerLevel = $this->_SIWECOSForm->get('dangerLevel');
            $author      = $this->_SIWECOSForm->get('author');
            $created     = $this->_SIWECOSForm->get('created');
        }

        $page = cSmartyBackend::getInstance(true);
        $page->assign('formAction', $formAction);
        $page->assign('domain', $domain);
        $page->assign('email', $email);
        $page->assign('password', $password);
        $page->assign('dangerLevel', $dangerLevel);
        $page->assign('userToken', $userToken);
        $page->assign('domainToken', $domainToken);
        $page->assign('author', $author);
        $page->assign('created', $created);

        $cGuiNotification = new cGuiNotification();

        if (empty($idsiwecos)) {
            // NOOP
        } elseif (empty($userToken)) {
            $notification = $cGuiNotification->returnNotification(
                cGuiNotification::LEVEL_WARNING,
                i18n('ERR_MISSING_USER_TOKEN', 'siwecos')
            );
            $this->set('s', 'notification', $notification);
        } else {
            $domainList = $this->_getDomainList($userToken);
            // error_log(print_r($domainList, true));
            if (!$this->_in_multiarray($domain, $domainList)) {
                $notification = $cGuiNotification->returnNotification(
                    cGuiNotification::LEVEL_WARNING,
                    i18n('ERR_DOMAIN_NOT_FOUND', 'siwecos')
                );
                $this->set('s', 'notification', $notification);
            } else {
                foreach ($domainList->domains as $ele) {
                    if ($ele->domain !== $domain) {
                        continue;
                    }

                    if (1 !== (int)$ele->verificationStatus) {
                        $reportHtml = $this->_showVerificationInfo();
                        break;
                    }

                    $domainResult = $this->_getDomainResult();
                    // error_log(print_r(json_decode($domainResult), true));

                    // href for scan start
                    $link = new cHTMLLink();
                    $link->setCLink($area, 4, self::SCAN_FORM);
                    $link->setCustom('idsiwecos', $idsiwecos);
                    $href = $link->getHref();

                    $reportPage = cSmartyBackend::getInstance(true);
                    $reportPage->assign('result', $domainResult);
                    $reportPage->assign('resultjson', json_decode($domainResult));
                    $reportPage->assign('scanHref', $href);
                    $reportPage->assign('howBtn', sprintf(i18n("BTN_HOWTO", 'siwecos'), $domain));
                    $reportPage->assign('language', $belang === 'de_DE' ? 'DE' : 'EN');
                    $reportHtml = $reportPage->fetch($cfg['templates']['siwecos_report_form']);
                }
            }
        }

        $page->assign('report', $reportHtml);

        $out = $page->fetch($cfg['templates']['siwecos_right_bottom_form']);

        return $out;
    }

    /**
     * Build and return form for SIWECOS verifacation forms.
     *
     * @return string
     * @throws cException
     */
    private function _showVerificationInfo()
    {
        $cfg = cRegistry::getConfig();

        $formAction = new cHTMLLink();
        $formAction->setCLink(cRegistry::getArea(), 4, self::VERIFICATION_FORM);
        $formAction->setCustom('idsiwecos', $this->_SIWECOSForm->get('idsiwecos'));

        $page = cSmartyBackend::getInstance(true);
        $page->assign('domain', $this->_SIWECOSForm->get('domain'));
        $page->assign('domainToken', $this->_SIWECOSForm->get('domainToken'));
        $page->assign('verificationHref', $formAction->getHref());
        $out = $page->fetch($cfg['templates']['siwecos_verification_form']);

        return $out;
    }

    /**
     * Request the info for current SIWECOS user.
     *
     * @return stdClass
     * @throws CurlException
     * @throws SIWECOSException
     */
    private function _login()
    {
        $email          = $this->_SIWECOSForm->get('email');
        $password       = empty($_POST['password']) ? '' : $_POST['password'];
        $data           = [
            'email'    => $email,
            'password' => $password,
        ];
        $header         = [
            'Accept: application/json',
            'Content-Type: application/json;charset=utf-8',
        ];
        $CurlConnection = new CurlService();
        $result         = $CurlConnection->post(SIWECOS_API_URL . '/users/login', $data, $header);
        if (200 !== (int)$CurlConnection->error['info']['http_code']) {
            throw new SIWECOSException($CurlConnection->error['resp']);
        } else {
            return $result;
        }
    }

    /**
     * Request the list of all domains of given user.
     *
     * @param string $userToken
     *
     * @return stdClass
     * @throws CurlException
     * @throws SIWECOSException
     */
    private function _getDomainList(string $userToken)
    {
        $header  = [
            'Accept: application/json',
            'Content-Type: application/json;charset=utf-8',
            'userToken: ' . $userToken,
        ];
        $service = new CurlService();
        $result  = $service->post(SIWECOS_API_URL . '/domains/listDomains', [], $header);

        if (200 !== (int)$service->error['info']['http_code']) {
            throw new SIWECOSException($service->error['resp']);
        }

        return $result;
    }

    /**
     * Request the report for the domain.
     *
     * @return stdClass
     * @throws CurlException
     * @throws SIWECOSException
     * @throws cException
     */
    private function _getDomainResult()
    {
        global $belang;

        $userToken      = $this->_SIWECOSForm->get('userToken');
        $domain         = $this->_SIWECOSForm->get('domain');
        $CurlConnection = new CurlService();
        $header         = [
            'Accept: application/json',
            'Content-Type: application/json;charset=utf-8',
            'userToken: ' . $userToken,
        ];
        if ($belang === 'de_DE') {
            $url = SIWECOS_API_URL . '/scan/result/de?domain=';
        } else {
            $url = SIWECOS_API_URL . '/scan/result/en?domain=';
        }

        $result = $CurlConnection->get($url . $domain, $header);
        if (200 !== (int)$CurlConnection->error['info']['http_code']) {
            error_log($CurlConnection->error);
            $msg = i18n('MISSING_REPORT', 'siwecos');
            throw new SIWECOSException($msg);
        } else {
            return $result;
        }
    }

    /**
     * Starts the scan for the current domain.
     *
     * @throws CurlException
     */
    private function _scanForm()
    {
        $idsiwecos   = $this->_SIWECOSForm->get('idsiwecos');
        $domain      = $this->_SIWECOSForm->get('domain');
        $userToken   = $this->_SIWECOSForm->get('userToken');
        $dangerLevel = $this->_SIWECOSForm->get('dangerLevel');

        if ($idsiwecos && $userToken) {
            $CurlConnection = new CurlService();
            // Submit new Domain
            $data   = [
                'dangerLevel' => $dangerLevel,
                'domain'      => $domain,
            ];
            $header = [
                'Accept: application/json',
                'Content-Type: application/json;charset=utf-8',
                'userToken: ' . $userToken,
            ];
            $CurlConnection->post(SIWECOS_API_URL . '/scan/start', $data, $header);
        }
    }

    /**
     * Validate form.
     *
     * @throws SIWECOSException
     * @throws cDbException
     * @throws cException
     */
    private function _validation()
    {
        global $client, $lang;
        $forms  = SIWECOSCollection::getByClientAndLang($client, $lang);
        $domain = trim(cSecurity::toString(cSecurity::unescapeDB($_POST['domain'])));
        if (!filter_var($domain, FILTER_VALIDATE_URL)) {
            throw new SIWECOSException(i18n('ERR_MALFORMED_URL', 'siwecos'));
        }
        $domain    = parse_url($domain);
        $domain    = $domain['scheme'] . '://' . $domain['host'];
        $email     = trim(cSecurity::toString(cSecurity::unescapeDB($_POST['email'])));
        $password  = trim(cSecurity::toString(cSecurity::unescapeDB($_POST['password'])));
        $idsiwecos = $this->_SIWECOSForm->get('idsiwecos');
        if (!$idsiwecos) {
            if ($this->_in_multiarray($domain, $forms)) {
                throw new SIWECOSException(i18n('ERR_DOMAIN_EXISTS', 'siwecos'));
            } elseif (!$domain || !$email || !$password) {
                throw new SIWECOSException(i18n('ERR_FORM_VALIDATION', 'siwecos'));
            }
        }
    }

    /**
     * @param $elem
     * @param $array
     *
     * @return bool
     */
    private function _in_multiarray($elem, $array)
    {
        foreach ($array as $key => $item) {
            if (is_array($item) || is_object($item)) {
                if ($this->_in_multiarray($elem, (array)$item)) {
                    return true;
                }
            } else {
                if ($elem === $item) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * store connection for form
     *
     * @throws CurlException
     * @throws SIWECOSException
     * @throws cDbException
     * @throws cException
     * @throws cInvalidArgumentException
     */
    private function _storeForm()
    {
        global $auth;
        $CurlConnection = new CurlService();
        $idsiwecos      = $this->_SIWECOSForm->get('idsiwecos');

        // read item data from form
        $domain = trim(cSecurity::toString(cSecurity::unescapeDB($_POST['domain'])));
        $domain = parse_url($domain);
        $domain = $domain['scheme'] . '://' . $domain['host'];
        $email  = trim(cSecurity::toString(cSecurity::unescapeDB($_POST['email'])));
        // $password    = trim(cSecurity::toString(cSecurity::unescapeDB($_POST['password'])));
        $dangerLevel = trim(cSecurity::toString(cSecurity::unescapeDB($_POST['dangerLevel'])));
        $author      = $auth->getUsername();
        $created     = date('Y-m-d H:i:s');

        // create new item for given client & language
        if (!$idsiwecos) {
            // create new item for given client & language
            $SIWECOSCollection  = new SIWECOSCollection();
            $this->_SIWECOSForm = $SIWECOSCollection->createNewItem(
                [
                    'idclient'    => cRegistry::getClientId(),
                    'idlang'      => cRegistry::getLanguageId(),
                    'domain'      => $domain,
                    'email'       => $email,
                    'dangerLevel' => $dangerLevel,
                    'author'      => $author,
                    'created'     => $created,
                ]
            );
            $idsiwecos          = $this->_SIWECOSForm->get('idsiwecos');
        }
        if ($domain !== $this->_SIWECOSForm->get('domain')) {
            $this->_SIWECOSForm->set('domain', $domain);
        }
        if ($email !== $this->_SIWECOSForm->get('email')) {
            $this->_SIWECOSForm->set('email', $email);
        }
        if ($dangerLevel !== $this->_SIWECOSForm->get('dangerLevel')) {
            $this->_SIWECOSForm->set('dangerLevel', $dangerLevel);
        }
        if ($author !== $this->_SIWECOSForm->get('author')) {
            $this->_SIWECOSForm->set('author', $author);
            $this->_SIWECOSForm->set('created', $created);
        }

        // store item
        if (false === $this->_SIWECOSForm->store()) {
            $msg = i18n('FORM_STORE_ERROR', 'siwecos');
            throw new SIWECOSException($msg);
        }

        $userToken = $this->_SIWECOSForm->get('userToken');
        if (!$userToken && $idsiwecos) {
            $result    = $this->_login();
            $userToken = $result->token;
            if ($userToken !== $this->_SIWECOSForm->get('userToken')) {
                $this->_SIWECOSForm->set('userToken', $userToken);
                // store item
                if (false === $this->_SIWECOSForm->store()) {
                    $msg = i18n('FORM_STORE_ERROR', 'siwecos');
                    throw new SIWECOSException($msg);
                }
            }
        }
        $domainToken = $this->_SIWECOSForm->get('domainToken');
        $addDomain   = true;
        if ($userToken) {
            $result = $this->_getDomainList($userToken);
            foreach ($result->domains as $ele) {
                if ($ele->domain === $domain) {
                    $domainToken = $ele->domainToken;
                    $addDomain   = false;
                }
            }
        }
        if ($addDomain) {
            $data        = [
                'danger_level' => $dangerLevel,
                'domain'       => $domain,
            ];
            $header      = [
                'Accept: application/json',
                'Content-Type: application/json;charset=utf-8',
                'userToken: ' . $userToken,
            ];
            $result      = $CurlConnection->post(SIWECOS_API_URL . '/domains/addNewDomain', $data, $header);
            $domainToken = $result->domainToken;
        }
        if ($domainToken !== $this->_SIWECOSForm->get('domainToken')) {
            $this->_SIWECOSForm->set('domainToken', $domainToken);
            // store item
            if (false === $this->_SIWECOSForm->store()) {
                $msg = i18n('FORM_STORE_ERROR', 'siwecos');
                throw new SIWECOSException($msg);
            }
        }
    }

    /**
     * Starts the scan for the current Domain.
     *
     * @throws CurlException
     */
    private function _startVerification()
    {
        $CurlConnection = new CurlService();
        $domain         = $this->_SIWECOSForm->get('domain');
        $userToken      = $this->_SIWECOSForm->get('userToken');
        $dangerLevel    = $this->_SIWECOSForm->get('dangerLevel');

        $data   = [
            'danger_level' => $dangerLevel,
            'domain'       => $domain,
        ];
        $header = [
            'Accept: application/json',
            'Content-Type: application/json;charset=utf-8',
            'userToken: ' . $userToken,
        ];
        $CurlConnection->post(SIWECOS_API_URL . '/domains/verifyDomain', $data, $header);
        // if (200 !== (int)$CurlConnection->error['info']['http_code']) {
        //     $msg = SIWECOS::i18n('no verification');
        //     throw new SIWECOSException($msg);
        // }
    }

    /**
     * Delete form.
     *
     * @throws SIWECOSException
     * @throws cDbException
     * @throws cException
     */
    private function _deleteForm()
    {
        $this->_SIWECOSForm->delete();
        $this->_SIWECOSForm = null;
    }
}