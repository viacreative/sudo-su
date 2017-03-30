<?php

namespace VIACreative\SudoSu;

use Illuminate\Support\Facades\Config;

class DomainRestricter
{
    /**
     * @param $domain
     *
     * @return bool
     */
    public function check($domain)
    {
        $allowedDomains = Config::get('sudosu.domains');

        // Split out the request url into the domain-name and port
        $requestData = parse_url($domain);
        $domain = $requestData['host'];
        $port = isset($requestData['port']) ? $requestData['port'] : null;

        // Create a regex based on the domains
        foreach($allowedDomains as $allowedDomain) {
            $regex = "/$allowedDomain$/";

            // Replace * with all chars
            $regex = str_replace('*', '(.*)', $regex);

            // Check the regex agains the domain.
            if ($port && preg_match($regex, "$domain:$port") || (!$port && preg_match($regex, $domain))) {
                return true;
            }
        }

        return false;
    }
}
