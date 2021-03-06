/**
 *
 * @title         wolfnetToolbar.src.js
 * @copyright     Copyright (c) 2012 - 2015, WolfNet Technologies, LLC
 *
 *                This program is free software; you can redistribute it and/or
 *                modify it under the terms of the GNU General Public License
 *                as published by the Free Software Foundation; either version 2
 *                of the License, or (at your option) any later version.
 *
 *                This program is distributed in the hope that it will be useful,
 *                but WITHOUT ANY WARRANTY; without even the implied warranty of
 *                MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *                GNU General Public License for more details.
 *
 *                You should have received a copy of the GNU General Public License
 *                along with this program; if not, write to the Free Software
 *                Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
(function($){

    var plugin = 'wolfnetToolbar';

    var stateKey = plugin + '.state';
    var optionsKey = plugin + '.options';

    var UPDATED = 'wolfnet.updated';
    var UPDATING = 'wolfnet.updating';

    var nextClass  = 'a.wolfnet_page_nav_next';
    var prevClass  = 'a.wolfnet_page_nav_prev';
    var itemsClass = 'wolfnet_page_items_select';
    var sortClass  = 'wolfnet_sortoptions';

    var defaultOptions = {
        maxResults       : 250,
        criteria         : {},
        itemsPerPageData : [5,10,15,20,25,30,35,40,45,50],
        sortOptionsData  : [
            {value:'price_desc', label:'Descending by Price'},
            {value:'price', label:'Ascending by Price'}
        ]
    };

    var escapeHtml = function(text)
    {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return text.replace(/[&<>"']/g, function (m) { return map[m]; });

    };

    var renderPropertyList = function(data)
    {

        data = ($.isArray(data.responseData.data.listing)) ? data.responseData.data.listing : [];

        var $container = this;
        var $listings = $('<div>').addClass('wolfnet_listings');

        for (var i=0, l=data.length; i<l; i++) {
            var cityState   = data[i].city + ', ' + data[i].state;
            var fullAddress = data[i].display_address + ', ' + cityState;

            var $listing = $('<div>')
                .attr('id', 'wolfnet_listing_' + data[i].property_id)
                .addClass('wolfnet_listing')
                .appendTo($listings);

            var $link = $('<a>')
                .attr({'href':data[i].property_url, 'title':fullAddress})
                .appendTo($listing);

            var $price = $('<span>')
                .addClass('wolfnet_price')
                .attr('itemprop', 'price')
                .html(data[i].listing_price.toString())
                .appendTo($link);

            var $location = $('<span>')
                .addClass('wolfnet_full_address')
                .attr('itemprop', 'street-address')
                .html(fullAddress)
                .appendTo($link);

        }

        $container.find('.wolfnet_listings').replaceWith($listings);

    };

    var getBedBathHTML = function(listing)
    {
        var bedBathHTML = '';

        var totalBeds = 0;
        if (listing.total_bedrooms !== '') {
            totalBeds += parseInt(listing.total_bedrooms);
        }

        var totalBaths = 0;
        if (listing.total_baths !== '') {
            totalBaths += parseInt(listing.total_baths);
        }

        if (totalBeds + totalBaths > 0) {
            bedBathHTML = (
                '<span class="wolfnet_bed_bath" title="' + escapeHtml(listing.bedsbaths_full) + '">' +
                (
                    totalBeds > 0 ? (
                        '<span class="wolfnet_beds">' +
                            totalBeds + ' ' + '<span class="wolfnet_label">Bedrooms</span>' +
                        '</span> ' +
                        (totalBaths > 0 ? '<span class="wolfnet_info_separator"></span> ' : '')
                    ) : ''
                ) +
                (
                    totalBaths > 0 ? (
                        '<span class="wolfnet_baths">' +
                            totalBaths + ' ' + '<span class="wolfnet_label">Bathrooms</span>' +
                        '</span> '
                    ) : ''
                ) +
                '</span> '
            );
        }

        return bedBathHTML;

    }

    var getBrandingHTML = function(listing)
    {
        var brandingHTML = '';

        if (listing.hasOwnProperty('branding')) {
            brandingHTML = (
                getBrandingLogoHTML(listing.branding) +
                '<span class="wolfnet_brandingMessage">' +
                    getBrandingMessageHTML(listing.branding) +
                '</span> '
            );
        }

        return brandingHTML;

    };

    var getBrandingLogoHTML = function(branding)
    {
        var brandingLogoHTML = '';

        if ($.trim(branding.logo || '') !== '') {
            var isIdx = (branding.type || '') === 'idx';
            brandingLogoHTML = (
                '<span class="wolfnet_brokerLogo' + (isIdx ? ' wolfnet_idxLogo' : '') + '">' +
                    '<img src="' + branding.logo + '" />' +
                '</span> '
            );
        }

        return brandingLogoHTML;

    }

    var getBrandingMessageHTML = function(branding)
    {
        var brandingMessageHTML = '';
        var brandingFields = [
            { name: 'courtesy_text',   className: 'wolfnet_brandingCourtesyText' },
            { name: 'agent_name',      className: 'wolfnet_brandingAgent wolfnet_brandingAgentName' },
            { name: 'agent_phone',     className: 'wolfnet_brandingAgent wolfnet_brandingAgentPhone' },
            { name: 'office_name',     className: 'wolfnet_brandingOffice wolfnet_brandingOfficeName' },
            { name: 'office_phone',    className: 'wolfnet_brandingOffice wolfnet_brandingOfficePhone' },
            { name: 'toll_free_phone', className: 'wolfnet_brandingTollFreePhone' }
        ];

        for (var i=0, l=brandingFields.length; i<l; i++) {
            if (
                branding.hasOwnProperty(brandingFields[i].name)
                && ($.trim(branding[brandingFields[i].name]) !== '')
            ) {
                brandingMessageHTML += (
                    '<span class="' + brandingFields[i].className + '">' +
                        branding[brandingFields[i].name] +
                    '</span> '
                );
            }
        }

        return brandingMessageHTML;

    };

    var renderListingGrid = function(data)
    {

        var listingsData = $.isArray(data.responseData.data.listing) ? data.responseData.data.listing : [];
        var templates = data.responseData.data.hasOwnProperty('templates') ? data.responseData.data.templates : {};

        var $container = this;
        var $listings = $('<div>').addClass('wolfnet_listings');

        if (templates.hasOwnProperty('listing')) {
            for (var i=0, l=listingsData.length; i<l; i++) {
                $listings.append(renderListing(listingsData[i], templates['listing']));
            }
        }

        $container.find('.wolfnet_listings').replaceWith($listings);

    };

    //replicating building of html dom in wolfnet.php, function: getHouseoverHtml
    var getHouseoverHtml = function(listing)
    {
        var concatHouseover = '';

        concatHouseover += '<div class="wolfnet_wntHouseOverWrapper">';
        concatHouseover += '    <a rel="follow" href="' + listing.property_url + '">';
        concatHouseover += '        <div data-property-id="' + listing.property_id + '" class="wntHOItem">';
        concatHouseover += '            <table class="wolfnet_wntHOTable"><tbody><tr>';
        concatHouseover += '                <td class="wntHOImgCol">';
        concatHouseover += '                    <div class="wolfnet_wntHOImg wolfnet_listingImage">';
        concatHouseover += '                        <img src="' + listing.thumbnail_url + '" />';
        concatHouseover += '                    </div>';
        if (listing.branding.logo !== '') {
            concatHouseover += '<div class="wolfnet_wntHOBroker wolfnet_brokerLogo' + (listing.branding.type === 'idx' ? ' wolfnet_idxLogo' : '') + '">';
            concatHouseover += '    <img src="' + listing.branding.logo + '" />';
            concatHouseover += '</span>';
        }
        concatHouseover += '                </td>';
        concatHouseover += '                <td>';
        concatHouseover += '                    <div class="wolfnet_wntHOContentContainer">';
        concatHouseover += '                        <div class="wolfnet_listingInfo">';
        concatHouseover += '                            <div class="wolfnet_price">' + listing.listing_price.toString() + '</div>';
        if (listing.total_bedrooms || listing.total_baths) {
            concatHouseover += '<span class="wolfnet_bed_bath">';
            if (listing.total_bedrooms) {
                concatHouseover += '<span class="wolfnet_beds">' + listing.total_bedrooms + '<span class="wolfnet_label">Bedrooms</span></span>';
                if (listing.total_baths) {
                    concatHouseover += '<span class="wolfnet_info_separator"></span>';
                }
            }
            if (listing.total_baths) {
                concatHouseover += '<span class="wolfnet_baths">' + listing.total_baths + '<span class="wolfnet_label">Bathrooms</span></span>';
            }
            concatHouseover += '</span>';
        }
        concatHouseover += '                        </div>';
        concatHouseover += '                        <div class="wolfnet_locationInfo">';
        concatHouseover += '                            <div class="wolfnet_address">' + listing.display_address + '</div>';
        concatHouseover += '                            <div class="wolfnet_location">' + listing.city + ', ' + listing.state + '</div>';
        concatHouseover += '                        </div>';
        concatHouseover += '                        <div class="wolfnet_branding" style="text-align: left; padding-top: 20px;">';
        concatHouseover += '                            <span class="wolfnet_brandingMessage">';
        concatHouseover += '                                <span class="wolfnet_brandingCourtesyText">' + listing.branding.courtesy_text + '</span>';
        concatHouseover += '                            </span>';
        concatHouseover += '                        </div>';
        concatHouseover += '                    </div>';
        concatHouseover += '                </td>';
        concatHouseover += '            </tr></tbody></table>';
        concatHouseover += '        </div>';
        concatHouseover += '    </a>';
        concatHouseover += '</div>';

        return concatHouseover;

    };

    var renderListing = function(listing, template)
    {
        var $listing = $(template);

        // Listing
        $listing.attr('id', listing.property_id);

        // Link
        $listing.find('a.wolfnet_listingLink').attr('href', listing.property_url);

        // Thumbnail
        $listing.find('.wolfnet_listingImage img').attr({
            'src': listing.thumbnail_url,
            'alt': 'Property for sale at ' + listing.address,
            'data-photo-url': listing.thumbnails_url
        });

        // Price
        $listing.find('.wolfnet_price')
            .attr('title', listing.listing_price)
            .text(listing.listing_price);

        // Beds / Baths
        $listing.find('.wolfnet_bed_bath').attr('title', listing.bedsbaths_full);
        if (listing.total_bedrooms) {
            $listing.find('.wolfnet_beds').text(listing.total_bedrooms).show();
            if (listing.total_baths) {
                $listing.find('.wolfnet_bed_bath .wolfnet_info_separator').show();
            }
        }
        if (listing.total_baths) {
            $listing.find('.wolfnet_baths').text(listing.total_baths).show();
        }

        // Location
        $listing.find('.wolfnet_locationInfo').attr('title', listing.address);
        $listing.find('.wolfnet_address').text(listing.display_address);
        $listing.find('.wolfnet_location').text(listing.location);
        $listing.find('.wolfnet_full_address').text(listing.address);

        // Branding
        var $branding = $listing.find('.wolfnet_branding');

        var $brokerLogo = $branding.find('.wolfnet_brokerLogo');
        if ((listing.branding.type === 'idx') && !$brokerLogo.is('.wolfnet_idxLogo')) {
            $brokerLogo.addClass('wolfnet_idxLogo');
        }
        if ($.trim(listing.branding.logo) !== '') {
            $brokerLogo.show().find('img').first().attr('src', listing.branding.logo);
        }

        $branding.find('.wolfnet_brandingCourtesyText').html(listing.branding.courtesy_text);
        $branding.find('.wolfnet_brandingAgent.wolfnet_brandingAgentName').html(listing.branding.agent_name);
        $branding.find('.wolfnet_brandingAgent.wolfnet_brandingAgentPhone').html(listing.branding.agent_phone);
        $branding.find('.wolfnet_brandingOffice.wolfnet_brandingOfficeName').html(listing.branding.office_name);
        $branding.find('.wolfnet_brandingOffice.wolfnet_brandingOfficePhone').html(listing.branding.office_phone);
        $branding.find('.wolfnet_brandingTollFreePhone').html(listing.branding.toll_free_phone);

        return $listing;

    }

    var populateMap = function(data)
    {
        var listingsData = ($.isArray(data.responseData.data.listing)) ? data.responseData.data.listing : [];
        var templates = data.responseData.data.hasOwnProperty('templates') ? data.responseData.data.templates : {};

        var $container = this;
        var componentMap = $container.find('.wolfnet_wntMainMap').data('map');
        var houseIcon = wolfnet_ajax.houseoverIcon;

        componentMap.removeAllShapes();


        for (var i=0, l=listingsData.length; i<l; i++) {
            var houseoverHtml = templates.hasOwnProperty('map') ? renderListing(listingsData[i], templates['map']).get(0) : '';

            if (
                ((listingsData[i].geo.lat !== 0) || (listingsData[i].geo.lng !== 0)) &&
                (!isNaN(listingsData[i].geo.lat) || !isNaN(listingsData[i].geo.lng)) &&
                (listingsData[i].geo.lat !== '' || listingsData[i].geo.lng !== '') &&
                ((listingsData[i].geo.lat >= -180) && (listingsData[i].geo.lat <= 180)) &&
                ((listingsData[i].geo.lng >= -180) && (listingsData[i].geo.lng <= 180))
            ) {
                var houseoverIcon = componentMap.mapIcon(houseIcon, 20, 20);
                var houseover = componentMap.poi(listingsData[i].geo.lat, listingsData[i].geo.lng, houseoverIcon, houseoverHtml, listingsData[i].property_id, listingsData[i].property_url);
                var boundsBuffer = 50;
                if (
                    (listingsData[i].geo.lat >= (componentMap.getBounds().lr.lat - boundsBuffer) &&
                    listingsData[i].geo.lat <= (componentMap.getBounds().ul.lat + boundsBuffer)) &&
                    (listingsData[i].geo.lng >=  (componentMap.getBounds().lr.lng - boundsBuffer) &&
                    listingsData[i].geo.lng <= (componentMap.getBounds().ul.lng + boundsBuffer))
                ){
                    componentMap.addPoi(houseover);
                }
            }
        }

        componentMap.bestFit();

    };

    // Take the data returned from an Ajax request and use it to render listings.
    var renderListings = function(data)
    {
        var $container = this;

        if ($container.is('.wolfnet_propertyList') || $container.is('.wolfnet_listingGrid')) {
            $container.find('.wolfnet_listings').children().remove();
        }

        if ($container.is('.wolfnet_propertyList')) {
            renderPropertyList.call($container, data);
        } else if ($container.is('.wolfnet_listingGrid')) {
            renderListingGrid.call($container, data);
            $container.wolfnetListingGrid('refresh');
        }

        if ($container.find('.wolfnet_wntMainMap').length > 0) {
            populateMap.call($container, data);
        }

        $container.trigger(UPDATED);

    };

    // Determine if page tools should be rendered and do so if they do.
    var renderItemsPerPageTool = function()
    {
        var $container = $(this);
        var $itemsDropDown = $container.find('span.' + itemsClass);
        var state = $container.data(stateKey);
        var options = $container.data(optionsKey);

        // If there is no dropdown and there should be, create one.
        if ($itemsDropDown.length === 0 && $container.is('.wolfnet_withPagination')) {
            var $select = $('<select>');

            // Register change event handler to trigger an update when the tool is changed.
            $select.change(function(){

				// Set new numrows
                state.numrows = $(this).val();

                // Reset page given new numrows
                state.page = (state.startrow + (state.numrows - 1)) / state.numrows;

                $container.data(stateKey, state);
                $container.find('span.' + itemsClass + ' select').val(state.numrows);

                methods.update.call($container);
            });

            for (var i=0,l=options.itemsPerPageData.length; i<l; i++) {
                var items = options.itemsPerPageData[i];

                $('<option>').attr('value', items).text(items).appendTo($select);

            }

            $itemsDropDown = $('<span>').addClass(itemsClass);
            $itemsDropDown.append($select).append('per page');
            $itemsDropDown.appendTo($container.find('.wolfnet_toolbar .wolfnet_page_items'));

        }

        $container.find('span.' + itemsClass + ' select').each(function(){
            $(this).val(Number(state.numrows));
        });

    };

    // Determine if sort tools should be rendered and do so if they do.
    var renderSortOptionsTool = function()
    {
        var $container = $(this);
        var $sortDropDown = $container.find('span.' + sortClass + ' select');
        var state = $container.data(stateKey);
        var options = $container.data(optionsKey);

        // If there is no dropdown and there should be, create one.
        if ($sortDropDown.length === 0 && $container.is('.wolfnet_withSortOptions')) {
            var $select = $('<select>');

            // Register change event handler to trigger an update when the tool is changed.
            $select.change(function(){
                state.sort = $(this).val();
                $container.data(stateKey, state);
                $container.find('span.' + sortClass + ' select').val(state.sort);
                methods.update.call($container);
            });

            for (var i=0,l=options.sortOptionsData.length; i<l; i++) {
                var sort = options.sortOptionsData[i];

                if (options.defaultSort == sort.value) {
					$sortOption = $('<option>').text(sort.label).attr({value:sort.value,selected:'selected'});
                } else {
					$sortOption = $('<option>').text(sort.label).attr('value', sort.value);
                }
				$sortOption.appendTo($select);

            }

            $sortDropDown = $('<span>').addClass(sortClass);
            $sortDropDown.append($select);
            $sortDropDown.appendTo($container.find('.wolfnet_toolbar .wolfnet_page_info'));

        }

    };

    var updateEvent = function(event)
    {
        var $container = $(this);
        var $nextBtn = $container.find(nextClass);
        var $prevBtn = $container.find(prevClass);
        var state = $container.data(stateKey);
        var options = $container.data(optionsKey);

		var listingCount = state.total_rows < state.maxresults ? state.total_rows : state.maxresults;
        var maxPage = Math.ceil(listingCount / state.numrows);

        if (state.page <= 1) {
            $prevBtn.addClass('wolfnet_disabled');
        }
        else {
            $prevBtn.removeClass('wolfnet_disabled');
        }

        if (state.page >= maxPage) {
            $nextBtn.addClass('wolfnet_disabled');
        }
        else {
            $nextBtn.removeClass('wolfnet_disabled');
        }

        // Update results count display
        var rowcountDisplay = (Number(state.startrow) - 1) + Number(state.numrows);

        if (Number(listingCount) < rowcountDisplay) {
            rowcountDisplay = Number(listingCount);
        }

        // Update page information
        $container.find('.wolfnet_page_start').text(state.startrow);
        $container.find('.wolfnet_page_end').text(rowcountDisplay);

        $('html,body').scrollTop($container.find('.wolfnet_toolbar').offset().top - 100);

        if ($container.is('.wolfnet_listingGrid') && $container.wolfnetListingGrid) {
            $container.wolfnetListingGrid("refresh", true);
        }

        $container.removeClass('wolfnet_refreshing');

        return true;

    };

    var methods = {

        // Initialize the plugin.  All of this code runs once per page request.
        init : function(options)
        {
            return this.each(function() {

                var $container = $(this);
                var opts = $.extend(true, {}, defaultOptions, options);
                var state = $.extend(true, {}, opts.criteria, opts, {page:1});

                delete opts.criteria;
                delete state.criteria;
                delete state.prices;
                delete state.ownertypes;
                delete state.savedsearches;

                for (var i in state) {
                    var isNameField  = (i.indexOf('_wpname') !== -1);
                    var isIdField    = (i.indexOf('_wpid') !== -1);
                    var isCheckField = (i.indexOf('_wpc') !== -1);
                    var isSelecField = (i.indexOf('_wps') !== -1);

                    if (isNameField || isIdField || isCheckField || isSelecField) {
                        delete state[i];
                    }

                }

                state.maxresults = opts.maxResults;

                if ($.inArray(Number(state.numrows), opts.itemsPerPageData) === -1) {
                    opts.itemsPerPageData.push(Number(state.numrows));
                }
                opts.itemsPerPageData.sort(function(a,b){
                    return a - b;
                });

                $container.data(optionsKey, opts);
                $container.data(stateKey, state);

                renderItemsPerPageTool.call($container);
                renderSortOptionsTool.call($container);

                $container.click(function(event){
                    var $target = $(event.target);

                    if ($target.is(nextClass) || $target.parent().is(nextClass)) {
                        event.preventDefault();
                        methods.next.call($container);
                        return false;
                    }
                    else if ($target.is(prevClass) || $target.parent().is(prevClass)) {
                        event.preventDefault();
                        methods.prev.call($container);
                        return false;
                    }

                    return true;

                });

                $container.on(UPDATED, updateEvent);

            });

        },

        // Perform Ajax request using the state data then update the listings content
        update : function()
        {
            return this.each(function() {
                var $container = $(this);
                var state = $container.data(stateKey);

                var getData = function() {

                    // alert(JSON.stringify(state));
                    var data = $.extend(state, {});
                    delete data.itemsPerPageData;
                    delete data.sortOptionsData;
                    return $.extend(state, {action:'wolfnet_get_listings'});
                };

                // perform ajax request
                $.ajax({
                    url : wolfnet_ajax.ajaxurl,
                    dataType : 'jsonp',
                    data : getData(),
                    beforeSend: function(xhr){
                        $container.addClass('wolfnet_refreshing').trigger(UPDATING);
                    }
                })
                // success: update listings
                .done(function(data, textStatus, xhr){
                    renderListings.call($container, data);
                })
                // failure: indicate failure to user
                .fail(function(xhr, textStatus){
                    $container.removeClass('wolfnet_refreshing');
                })
                .always(function(){
                });

            });

        },

        // Update state data with new page and then call the update method to retrieve new data.
        next : function()
        {
            return this.each(function() {
                var $container = $(this);
                var state = $container.data(stateKey);
                var newPage = state.page + 1;

                methods.page.call($container, newPage);

            });

        },

        // Update state data with new page and then call the update method to retrieve new data.
        prev : function()
        {
            return this.each(function() {
                var $container = $(this);
                var state = $container.data(stateKey);
                var newPage = state.page - 1;

                methods.page.call($container, newPage);

            });

        },

        // Update the state data to a specific page number.
        page : function(page)
        {
            return this.each(function() {
                var $container = $(this);
                var options = $container.data(optionsKey);
                var state = $container.data(stateKey);
                var maxPage = Math.ceil(options.maxResults / Number(state.numrows));
                var newPage = page;

                // Ensure that only valid pages can be requested.
                if (newPage < 1) {
                    newPage = maxPage;
                }
                else if (newPage > maxPage) {
                    newPage = 1;
                }
                var newStartRow = ((newPage - 1) * Number(state.numrows)) + 1;

                // update the current page.
                $container.data(stateKey, $.extend(state, {page:newPage, startrow:newStartRow}));

                methods.update.call($container);

            });

        }

    };

    $.fn[plugin] = function(method)
    {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' +  method + ' does not exist on jQuery.' + pluginName);
        }

    };

})(jQuery);
