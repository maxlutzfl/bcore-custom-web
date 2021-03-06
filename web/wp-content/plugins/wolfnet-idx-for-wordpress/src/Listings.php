<?php

/**
 * WolfNet Listings
 *
 * This class represents the listing data and associated functions to retrieve
 * and manipulate it.
 *
 * @package Wolfnet
 * @copyright 2015 WolfNet Technologies, LLC.
 * @license GPLv2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 *
 */
class Wolfnet_Listings
{
	/**
    * This property holds the current instance of the Wolfnet_Plugin.
    * @var Wolfnet_Plugin
    */
    protected $plugin = null;


    public function __construct($plugin) {
        $this->plugin = $plugin;
    }


    /**
     * Prepare an array usable by the Wolfnet API for a /listing query
     * @param  array  $criteria mixed array containing query api parameter. this can also contain
     * other items not used by the API, these will be stripped out.
     * @return array            Return array containing only query parameters to be passed to the api
     */
    public function prepareListingQuery(array $criteria)
    {

        // Array of aliased criteria
        $criteriaAlias = array(
            'priceReduced' => 'pricereduced',
            'exactcity' => 'exact_city',
            'maxprice' => 'max_price',
            'minprice' => 'min_price',
            'zipcode' => 'zip_code',
            'ownertype' => 'owner_type',
            'maxresults' => 'maxrows',
        );

        // Translate aliases to their canonical version and then removed the alias from the array
        foreach ($criteriaAlias as $alias => $crit) {
            if (array_key_exists($alias, $criteria)) {
                if (!array_key_exists($crit, $criteria)) {
                    $criteria[$crit] = $criteria[$alias];
                }

                unset($criteria[$alias]);

            }

        }

        // Array of boolean criteria
        $boolCriteria = array(
            'agent_only',
            'office_only',
            'agent_office_only',
            'business_with_real_estate',
            'commercial',
            'commercial_lease',
            'condo',
            'condo_townhouse',
            'duplex',
            'exact_property_id',
            'farm_hobby',
            'foreclosure',
            'gated_community',
            'half_duplex',
            'has_basement',
            'has_family_room',
            'has_fireplace',
            'has_garage',
            'has_golf',
            'has_horse_property',
            'has_mountain_view',
            'has_pool',
            'has_waterfront',
            'waterfront',
            'has_waterview',
            'has_lakefront',
            'industrial',
            'investment',
            'loft',
            'lots_acreage',
            'mixed_use',
            'mobile_home',
            'model',
            'multi_family',
            'new_and_updated',
            'new_construction',
            'newlistings',
            'on_golf_course',
            'open_house',
            'pricereduced',
            'property_view',
            'redraw_map_bounds',
            'residential_lease',
            'residential_lease_detached',
            'retail_store',
            'shortsale',
            'similar_listings',
            'single_family',
            'single_family_detached',
            'sold',
            'townhouse',
            'one_story',
            'two_story',
            'three_plus_story',
        );

        // Translate pseudo boolean values to true boolean values
        foreach ($boolCriteria as $bool) {
            if (array_key_exists($bool, $criteria)) {
                $criteria[$bool] = $this->convertBool($criteria[$bool]);
            }

        }


        if (array_key_exists('exact_city', $criteria)) {
            $hasCity = array_key_exists('city', $criteria);

            if ($criteria['exact_city'] === null || trim($criteria['exact_city']) === '') {
                unset($criteria['exact_city']);
            }

        }

        // Translate legacy "primary search type" criteria to API criteria
        if (array_key_exists('primarysearchtype', $criteria)) {
            switch ($criteria['primarysearchtype']) {

                case 'sold':
                    $criteria['sold'] = 1;
                    break;

                case 'open':
                    $criteria['open_house'] = 1;
                    break;

                case 'foreclosure':
                    $criteria['foreclosure'] = 1;
                    break;

            }

            unset($criteria['primarysearchtype']);

        }

        // Translate legacy "owner type" criteria to API criteria
        // agent_only, office_only, and agent_office_only are legacy cases.
        if (array_key_exists('owner_type', $criteria)) {
            switch ($criteria['owner_type']) {

                case 'agent':
                    $criteria['agent_only'] = 1;
                    break;
                case 'agent_only':
                    $criteria['agent_only'] = 1;
                    break;

                case 'broker':
                    $criteria['office_only'] = 1;
                    break;
                case 'office_only':
                    $criteria['office_only'] = 1;
                    break;

                case 'agent_broker':
                    $criteria['agent_office_only'] = 1;
                    break;
                case 'agent_office_only':
                    $criteria['agent_office_only'] = 1;
                    break;

            }

            unset($criteria['owner_type']);

        }

        // Plugin specific criteria
        $pluginCriteria = array(
            'owner_type',
            'ownertypes',
            'paginated',
            'criteria',
            'mode',
            'savedsearch',
            'savedsearches',
            'wntSavedSearches',
            'key',
            'keyid',
            'title',
            'class',
            'maptype',
            'maptypes',
            'mapEnabled',
            'sortoptions',
            'maxresults',
            'autoplay',
            'direction',
            'speed',
            'prices',
        );

        $pluginCriteriaPattern = array(
            '/.*_wpid$/',
            '/.*_wpname$/',
            '/.*_wps$/',
            '/.*_wpc$/',
        );

        $criteriaKeys = array_keys($criteria);

        foreach ($pluginCriteriaPattern as $pattern) {
            $pluginCriteria = array_merge($pluginCriteria, preg_grep($pattern, $criteriaKeys));
        }

        // Remove Plugin specific values
        foreach ($pluginCriteria as $crit) {
            if (array_key_exists($crit, $criteria)) {
                unset($criteria[$crit]);
            }

        }

        // Remove non-scalar values
        foreach ($criteria as $crit => $value) {
            if (!is_scalar($value)) {
                unset($criteria[$crit]);
            }
        }

        return $criteria;

    }


    /**
     * Prepare the listings for display. Pass in the array returned from the api /listing method.
     * Format fields & add missing data items needed for displays
     * @param  array $data   the array as returned from the api /listing method
     * @param  string        the api key
     * @return array         returns the same array structure with additional info
     */
    public function augmentListingsData(&$data, $key, array $templates = array())
    {

        if (is_array($data['responseData']['data'])) {
            $listingsData = &$data['responseData']['data']['listing'];
        }
        $data['responseData']['data']['templates'] = $templates;

        $br_logo = $this->plugin->data->getBrLogo($key);

        if (array_key_exists('src', $br_logo)) {
            $br_logo_url =  $br_logo['src'];
        }

        $show_logo = $data['responseData']['metadata']['display_rules']['results']['display_broker_reciprocity_logo'];
        $wnt_base_url = $this->plugin->data->getBaseUrl($key);

        // Include template(s)
        $vars = array(
            'listing' => $this->getDefaults()
        );
        $data['responseData']['data']['templates'] = array();
        if (in_array('listing', $templates)) {
            $data['responseData']['data']['templates']['listing'] = $this->plugin->views->listingView($vars);
        }
        if (in_array('map', $templates)) {
            $data['responseData']['data']['templates']['map'] = $this->plugin->views->houseOver($vars);
        }

        // loop over listings
        foreach ($listingsData as &$listing) {

            // If sold_price is empty, use listing_price
            if (empty($listing['sold_price'])) {
                $listing['listing_price'] = '$' . number_format($listing['listing_price']);

            // If sold_price is populated, use that
            } else {
                $listing['listing_price'] = '$' . number_format($listing['sold_price']);
            }

            if ($show_logo && empty($listing['branding']['logo'])&& !empty($br_logo_url)) {
                $listing['branding']['logo'] = $br_logo_url;
            }

            if (empty($listing['property_url'])) {
                $listing['property_url'] = $wnt_base_url . '/?action=listing_detail&property_id='
                    . $listing['property_id'];
            }

            $scriptData = $this->plugin->template->localizedScriptData();
            $listing['thumbnails_url'] = $scriptData['ajaxurl']
                . '?action=wolfnet_listing_photos&property_id=' . $listing['property_id'];

            $listing['location'] = $listing['city'];

            if ($listing['city'] != '' && $listing['state'] != '') {
                $listing['location'] .= ', ';
            }

            $listing['location'] .= $listing['state'];
            $listing['location'] .= ' ' . $listing['zip_code'];

            $listing['bedsbaths'] = '';

            if (is_numeric($listing['total_bedrooms']) && ($listing['total_bedrooms'] > 0 )) {
                $listing['bedsbaths'] .= $listing['total_bedrooms'] . 'bd';
            }

            $listing['total_baths'] = 0;

            if (is_numeric($listing['total_partial_baths'])) {
                $listing['total_baths'] += $listing['total_partial_baths'];
            }

            if (is_numeric($listing['total_full_baths'])) {
                $listing['total_baths'] += $listing['total_full_baths'];
            }

            if (!empty($listing['bedsbaths']) && is_numeric($listing['total_baths']) && ($listing['total_baths'] > 0)) {
                $listing['bedsbaths'] .= '/';
            }

            if (is_numeric($listing['total_baths']) && ($listing['total_baths'] > 0)) {
                $listing['bedsbaths'] .= $listing['total_baths'] . 'ba';
            }

            $listing['bedsbaths_full'] = '';

            if (is_numeric($listing['total_bedrooms'])) {
                $listing['bedsbaths_full'] .= $listing['total_bedrooms'] . ' Bedrooms';
            }

            if (is_numeric($listing['total_bedrooms']) && is_numeric($listing['total_baths'])) {
                $listing['bedsbaths_full'] .= ' & ';
            }

            if (is_numeric($listing['total_baths'])) {
                $listing['bedsbaths_full'] .= $listing['total_baths'] . ' Bathrooms';
            }

            $listing['address'] = $listing['display_address'];

            if ($listing['city'] != '' && $listing['address'] != '') {
                $listing['address'] .= ', ';
            }

            $listing['address'] .= $listing['city'];

            if ($listing['state'] != '' && $listing['address'] != '') {
                $listing['address'] .= ', ';
            }

            $listing['address'] .= ' ' . $listing['state'];
            $listing['address'] .= ' ' . $listing['zip_code'];

        }

        return $data;

    }


    public function getPhotos($propertyId, $keyId=null)
    {
        if (($keyId == null) || ($keyId == '')) {
            $productKey = $this->plugin->keyService->getDefault();
        } else {
            $productKey = $this->plugin->keyService->getById($keyId);
        }
        try {
            $data = $this->plugin->api->sendRequest(
                $productKey,
                '/listing/' . $propertyId . '/photos',
                'GET'
            );
        } catch (Wolfnet_Exception $e) {
            return $this->plugin->displayException($e);
        }
        return $data['responseData']['data'];
    }


    public function getDefaults()
    {

        return array(
            //'acres'          => '',
            'address'          => '',
            'bedsbaths'        => '',
            'bedsbaths_full'   => '',
            'branding'         => array(
                'agent_name'             => '',
                'agent_phone'            => '',
                'courtesy_text'          => '',
                //'email'                => '',
                'logo'                   => '',
                //'map_logo'             => '',
                //'mobile_app_url'       => '',
                //'mobile_phone'         => '',
                'office_name'            => '',
                //'office_name_prefix'   => '',
                'office_phone'           => '',
                //'owner'                => false,
                /*'social_media'         => array(
                    'facebook'    => '',
                    'google_plus' => '',
                    'linked_in'   => '',
                    'twitter'     => '',
                ),*/
                'toll_free_phone'        => '',
                'type'                   => '',
                //'url'                  => '',
            ),
            //'city'                  => '',
            //'county'                => '',
            'display_address'         => '',
            //'display_mlsnumber'     => '',
            //'featured'              => false,
            //'geo'                   => array( 'lat' => '', 'lng' => '', ),
            //'is_foreclosure'        => false,
            //'is_shortsale'          => false,
            //'list_date'             => '',
            'listing_price'           => '',
            //'listing_status'        => '',
            //'listings_at_location'  => array(),
            'location'                => '',
            //'market_name'           => '',
            //'open_house'            => array(),
            //'photo_url'             => '',
            'property_id'             => '',
            /*'property_type'         => array(
                'general_sub_type'    => '',
                'detail'              => array(),
                'general_type'        => '',
            ),*/
            'property_url'            => '',
            //'sold_date'             => '',
            //'sold_price'            => '',
            //'square_feet'           => '',
            //'state'                 => '',
            //'status'                => array(),
            'thumbnail_url'           => '',
            'thumbnails_url'          => '',
            'total_baths'             => '',
            'total_bedrooms'          => '',
            //'total_full_baths'      => '',
            //'total_partial_baths'   => '',
            /*'vow'                   => array(
                'days_on_market'           => '',
                'original_listing_price'   => '',
                'sort'                     => array(),
            ),*/
            //'zip_code'              => '',
        );

    }


    public function getSample()
    {

        return array_merge($this->getDefaults(), array(
            'address'          => '1234 Green Hills Rd, Austin, TX 78701',
            'bedsbaths'        => '4bd/3ba',
            'bedsbaths_full'   => '4 Bedrooms & 3 Bathrooms',
            'display_address'  => '1234 Green Hills Rd',
            'listing_price'    => '$649,000',
            'location'         => 'Austin, TX 78701',
            'property_id'      => '1234567',
            'thumbnail_url'    => '//common.wolfnet.com/wordpress/sample-thumb.jpg',
            'thumbnails_url'   => '',
            'total_baths'      => 3,
            'total_bedrooms'   => 4,
        ));

    }


    /**
     * The API expects boolean values to be passed as 0 or 1.
     * shortcodes arguments from saved searches save boolean args in many non constant ways
     * Y/N, y/n, true/false, 0/1. This method converts these to API friendly 0/1
     * @param  string   to be converted to 1 or 0
     * @return int      API friendly 1 or 0
     */
    private function convertBool($to_bool)
    {
        $bool_true = array(true,'Y','y',1,'true','yes');

        return (in_array($to_bool, $bool_true)) ? 1 : 0 ;

    }
}
