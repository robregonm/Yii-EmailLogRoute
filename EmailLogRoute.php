<?php
/**
 *
 * @author Ricardo Obregón <ricardo@obregon.co>
 * @created 30/06/13 12:02 PM
 */

class EmailLogRoute extends CEmailLogRoute
{
    protected function sendEmail($email, $subject, $message)
    {
        $app = Yii::app();
        $user = $app->user;
        $request = $app->request;

        $details = array();

        if ($user->isGuest) {
            $activeUser = 'Guest';
        } else {
            $activeUser = $user->name . "({$user->id})";
        }
        $details[] = 'Active User: ' . $activeUser;

        $details[] = 'IP: ' . $request->userHostAddress;

        $details[] = 'User-Agent: ' . $request->userAgent;

        // Uncommment only if GEOIP module is active
        //$details[] = 'Country: ' . $_SERVER["GEOIP_COUNTRY_NAME"] . ' - ' . $_SERVER["GEOIP_COUNTRY_CODE"];

        if (!empty($_GET)) {
            $details[] = 'GET Data:' . "\r\n" . wordwrap(var_export($_GET, true), 70);
        }
        if (!empty($_POST)) {
            $details[] = 'POST Data:' . "\r\n" . wordwrap(var_export($_POST, true), 70);
        }
        if (!empty($_COOKIE)) {
            $details[] = wordwrap(var_export($request->cookies, true));
            $details[] = 'COOKIE Data:' . "\r\n" . wordwrap(var_export($_COOKIE, true), 70);
        }
        if (!empty($_FILES)) {
            $details[] = 'FILES Data:' . "\r\n" . wordwrap(var_export($_FILES, true), 70);
        }

        $detailStr = "\r\n--- REQUEST DETAILS ---\r\n" . implode("\r\n", $details);

        return parent::sendEmail($email, $subject, $message . $detailStr);
    }
}
