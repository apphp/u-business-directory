var globalDebug = false;

/**
 * Finds longitude and attitude by address
 * @param frm
 */
function listings_FindCoordinates(frm)
{
    // Define this to prevent name overlapping
    var $ = jQuery;

    var token = $('#'+frm).find('input[name=APPHP_CSRF_TOKEN]').val();
    var address = $('#'+frm).find('input[name=business_address]').val();
    var $longitude = $('#'+frm).find('input[name=region_longitude]');
    var $latitude = $('#'+frm).find('input[name=region_latitude]');

    if(address == ''){
        if(globalDebug){
            console.error('empty address');
        }
        alert("Address cannot be empty! Please enter an address before you're trying to find coordinates.");
        $('#'+frm).find('input[name=region_name]').focus();
        return;
    }

    $.ajax({
        url: 'AjaxHandler/findCoordinates',
        global: false,
        type: 'POST',
        data: ({
            APPHP_CSRF_TOKEN: token,
            act: 'send',
            address: address
        }),
        dataType: 'html',
        async: true,
        error: function(html){
            //alert("AJAX: cannot connect to the server or server response error! Please try again later.");
            if(globalDebug){
                console.error("AJAX: cannot connect to the server or server response error!");
            }
        },
        success: function(html){
            try{
                var obj = $.parseJSON(html);
                var changeValues = false;

                if(obj.status == "1"){
                    if($longitude.val() == '' && $latitude.val() == '' ){
                        changeValues = true;
                    }else{
                        if(obj.longitude != $longitude.val() || obj.latitude != $latitude.val()){
                            if(confirm('The new result is different from the previous coordinates! Do yuo want to replace them anyway?')){
                                changeValues = true;
                            }
                        }
                    }

                    if(changeValues){
                        if(obj.longitude != '') $longitude.val(obj.longitude);
                        if(obj.latitude != '') $latitude.val(obj.latitude);
                    }
                }else{
                    alert("Wrong parameters passed or incorrect address! Please try to write this address in a different format.");
                }
            }catch(err){
                //alert("An error occurred while receiving data! Please try again later.");
                if(globalDebug){
                    console.error("An error occurred while receiving data!");
                    console.error(err);
                }
            }
        }
    });
}


/**
 * Change country from dropdown box
 * @param frm
 * @param country
 * @param state
 * @return Object $.ajax() or null
 */
function regions_ChangeSubRegions(frm, region, subregion)
{
    // define this to prevent name overlapping
    var $ = jQuery;
    var out = null;

    var token = $('#'+frm).find('input[name=APPHP_CSRF_TOKEN]').val();
    var subRegionId = $('#'+frm).find('*[name=subregion_id]').attr('id');
    var regionId = (region != null) ? +region : '';
    subregion = (subregion != null) ? +subregion : '';

    if(!regionId){
        $("#"+subRegionId).html('');
        $("#"+subRegionId).empty();
        $("<option />", {val: "0", text: "--"}).appendTo("#"+subRegionId);
    }else{
        out = $.ajax({
            url: 'AjaxHandler/getSubRegions',
            global: false,
            type: 'POST',
            data: ({
                APPHP_CSRF_TOKEN: token,
                act: 'send',
                parent_id: regionId
            }),
            dataType: 'html',
            async: true,
            error: function(html){
                //alert("AJAX: cannot connect to the server or server response error! Please try again later.");
                if(globalDebug){
                    cosole.error("AJAX: cannot connect to the server or server response error!");
                }
            },
            success: function(html){
                try{
                    var obj = $.parseJSON(html);
                    if(obj[0].status == "1"){
                        $("#"+subRegionId).html('');
                        $("#"+subRegionId).empty();
                        // add empty option
                        $("<option />", {val: "0", text: "--"}).appendTo("#"+subRegionId);
                        if(obj.length > 1){
                            for(var i = 1; i < obj.length; i++){
                                if(obj[i].id == subregion && subregion != ''){
                                    $("<option />", {val: obj[i].id, text: obj[i].name, selected: true}).appendTo("#"+subRegionId);
                                }else{
                                    $("<option />", {val: obj[i].id, text: obj[i].name}).appendTo("#"+subRegionId);
                                }
                            }
                        }
                    }else{
                        //alert("An error occurred while receiving data! Please try again later.");
                        if(globalDebug){
                            console.error("An error occurred while receiving data!");
                        }
                    }
                }catch(err){
                    //alert("An error occurred while receiving data! Please try again later.");
                    //console.error("An error occurred while receiving data!");
                    if(globalDebug){
                        console.error(err);
                    }
                }
            }
        });
    }

    return out;
}

/**
 * Validates form fields and submit the form
 * @param object el form element
 */
function customers_RegistrationForm(el)
{
    if(el == null || jQuery(el).hasClass('hover')) return false;
    // define this to prevent name overlapping
    var $ = jQuery;

    ///var customers_registrationForm = $(el).closest('form');
    var token = $(el).closest('form').find('input[name=APPHP_CSRF_TOKEN]').val();
    var re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,7})+$/;

    var firstName = $('#first_name').val();
    var isFirstNameRequired = $('#first_name').data('required');
    var lastName = $('#last_name').val();
    var isLastNameRequired = $('#last_name').data('required');
    var birthDate = $('#birth_date').val();
    var isBirthDateRequired = $('#birth_date').data('required');
    var website = $('#website').val();
    var isWebsiteRequired = $('#website').data('required');
    var company = $('#company').val();
    var isCompanyRequired = $('#company').data('required');
    var phone = $('#phone').val();
    var isPhoneRequired = $('#phone').data('required');
    var fax = $('#fax').val();
    var isFaxRequired = $('#fax').data('required');
    var address = $('#address').val();
    var isAddressRequired = $('#address').data('required');
    var address2 = $('#address_2').val();
    var isAddress2Required = $('#address_2').data('required');
    var city = $('#city').val();
    var isCityRequired = $('#city').data('required');
    var zipCode = $('#zip_code').val();
    var isZipCodeRequired = $('#zip_code').data('required');
    var countryCode = $('#country_code').val();
    var isCountryCodeRequired = $('#country_code').data('required');
    var state = $('#state').val();
    var isStateRequired = $('#state').data('required');
    var email = $('#email').val();
    var isEmailRequired = $('#email').data('required');
    var username = $('#username').val();
    var isUsernameRequired = $('#username').data('required');
    var password = $('#password').val();
    var isPasswordRequired = $('#password').data('required');
    var confirmPassword = $('#confirm_password').val();
    var isConfirmPasswordRequired = $('#confirm_password').data('required');
    var confirmPassword = $('#confirm_password').val();
    var notifications = $('#notifications').is(':checked') ? 1 : 0;
    var captcha = $('#captcha_validation').val();
    var isCaptchaRequired = $('#captcha_validation').data('required');

    $('.error').hide();
    $('.success').hide();
    $('#messageError').hide();
    $('#messageInfo').show();

    if(isFirstNameRequired && !firstName){
        $('#first_name').focus();
        $('#firstNameErrorEmpty').show();
    }else if(isLastNameRequired && !lastName){
        $('#last_name').focus();
        $('#lastNameErrorEmpty').show();
    }else if(isBirthDateRequired && !birthDate){
        $('#birth_date').focus();
        $('#birthDateErrorEmpty').show();
    }else if(isWebsiteRequired && !website){
        $('#website').focus();
        $('#websiteErrorEmpty').show();
    }else if(isCompanyRequired && !company){
        $('#company').focus();
        $('#companyErrorEmpty').show();
    }else if(isPhoneRequired && !phone){
        $('#phone').focus();
        $('#phoneErrorEmpty').show();
    }else if(isFaxRequired && !fax){
        $('#fax').focus();
        $('#faxErrorEmpty').show();
    }else if(isAddressRequired && !address){
        $('#address').focus();
        $('#addressErrorEmpty').show();
    }else if(isAddress2Required && !address2){
        $('#address_2').focus();
        $('#address2ErrorEmpty').show();
    }else if(isCityRequired && !city){
        $('#city').focus();
        $('#cityErrorEmpty').show();
    }else if(isZipCodeRequired && !zipCode){
        $('#zip_code').focus();
        $('#zipcodeErrorEmpty').show();
    }else if(isCountryCodeRequired && !countryCode){
        $('#countryCode').focus();
        $('#countrycodeErrorEmpty').show();
    }else if(isStateRequired && !state){
        $('#state').focus();
        $('#stateErrorEmpty').show();
    }else if(isEmailRequired && !email){
        $('#email').focus();
        $('#emailErrorEmpty').show();
    }else if(email && !re.test(email)){
        $('#email').focus();
        $('#emailErrorValid').show();
    }else if(isUsernameRequired && !username){
        $('#username').focus();
        $('#usernameErrorEmpty').show();
    }else if(isPasswordRequired && !password){
        $('#password').focus();
        $('#passwordErrorEmpty').show();
    }else if(isConfirmPasswordRequired && !confirmPassword){
        $('#confirm_password').focus();
        $('#confpasswordErrorEmpty').show();
    }else if(isConfirmPasswordRequired && confirmPassword != password){
        $('#confirm_password').focus();
        $('#confpasswordErrorEqual').show();
    }else if(isCaptchaRequired && !captcha){
        $('#captcha_validation').focus();
        $('#captchaError').show();
    }else{

        $(el).html($(el).data('sending'));
        $(el).addClass('hover');
        $(el).attr('disabled','disabled');

        $.ajax({
            url: 'customers/registration',
            global: false,
            type: 'POST',
            data: ({
                APPHP_CSRF_TOKEN: token,
                act: "send",
                first_name: firstName,
                last_name: lastName,
                birth_date: birthDate,
                website: website,
                company: company,
                phone: phone,
                fax: fax,
                address: address,
                address_2: address2,
                city: city,
                zip_code: zipCode,
                country_code: countryCode,
                state: state,
                email: email,
                username: username,
                password: password,
                confirm_password: confirmPassword,
                notifications: notifications,
                captcha: captcha
            }),
            dataType: 'html',
            async: true,
            error: function(html){
                $('.error').hide();
                $('#messageInfo').hide();
                $('#messageError').show();
            },
            success: function(html){
                try{
                    var obj = $.parseJSON(html);
                    if(obj.status == '1'){
                        $('.error').hide();
                        $('#first_name').val('');
                        $('#last_name').val('');
                        $('#birth_date').val('');
                        $('#website').val('');
                        $('#company').val('');
                        $('#phone').val('');
                        $('#fax').val('');
                        $('#address').val('');
                        $('#address_2').val('');
                        $('#city').val('');
                        $('#zip_code').val('');
                        //$('#country_code').val('');
                        //$('#state').val('');
                        $('#email').val('');
                        $('#username').val('');
                        $('#password').val('');
                        $('#confirm_password').val('');
                        $('#captcha_validation').val('');

                        $('.registration-form-content').slideUp();
                        $('html, body').animate({
                            scrollTop: $('#main').offset().top
                        }, 1000);
                        $('#messageSuccess').show();
                    }else{
                        customers_RaiseError(el, obj.error, obj.error_field);
                    }
                }catch(err){
                    customers_RaiseError(el, err.message);
                }
            }
        });
    }
    return false;
}

/**
 * Validates form fields and submit the form
 * @param object el form element
 */
function customers_RestorePasswordForm(el)
{
    if(el == null) return false;
    // define this to prevent name overlapping
    var $ = jQuery;

    var frm = $(el).closest('form');
    var re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,7})+$/;

    var email = $('#email').val();

    $('.alert').hide();
    $('.error').hide();
    $('.success').hide();
    $('#messageError').hide();
    $('#messageInfo').show();

    if(!email){
        $('#email').focus();
        $('#emailErrorEmpty').show();
    }else if(email && !re.test(email)){
        $('#email').focus();
        $('#emailErrorValid').show();
    }else{
        $(el).html($(el).data('sending'));
        $(el).addClass('hover');
        $(el).attr('disabled','disabled');

        frm.submit();
        return true;
    }
    // prevent the default form submission occurring
    return false;
}

/**
 * Validates form fields and submit the side login form
 * @param object el form element
 */
function customers_SideLoginForm(el)
{
    if(el == null) return false;
    // define this to prevent name overlapping
    var $ = jQuery;

    var username = $('#ait-login-form-widget input[name=login_username]').val();
    var password = $('#ait-login-form-widget input[name=login_password]').val();

    $('.error').hide();

    if(!username){
        $('#ait-login-form-widget #login_username').focus();
        $('#ait-login-form-widget #usernameLoginErrorEmpty').show();
    }else if(!password){
        $('#ait-login-form-widget #login_password').focus();
        $('#ait-login-form-widget #passwordLoginErrorEmpty').show();
    }else{
        $(el).closest('form').submit();
        return true;
    }
    return false;
}

/**
 * Validates form fields and submit the side simple registration form
 * @param object el form element
 */

/**
 * Validates form fields and submit the form
 * @param object el form element
 */
function customers_SideRegisterForm(el)
{
    if(el == null || jQuery(el).hasClass('hover')) return false;
    // define this to prevent name overlapping
    var $ = jQuery;

    ///var customers_registrationForm = $(el).closest('form');
    var token = $(el).closest('form').find('input[name=APPHP_CSRF_TOKEN]').val();
    var re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,7})+$/;

    var username    = $('#ait-register-form-widget input[name=username]').val();
    var password    = $('#ait-register-form-widget input[name=password]').val();
    var firstName   = $('#ait-register-form-widget input[name=first_name]').val();
    var lastName    = $('#ait-register-form-widget input[name=last_name]').val();
    var birthDate   = $('#ait-register-form-widget input[name=birth_date]').val();
    var website     = $('#ait-register-form-widget input[name=website]').val();
    var company     = $('#ait-register-form-widget input[name=company]').val();
    var phone       = $('#ait-register-form-widget input[name=phone]').val();
    var fax         = $('#ait-register-form-widget input[name=fax]').val();
    var email       = $('#ait-register-form-widget input[name=email]').val();
    var address     = $('#ait-register-form-widget input[name=address]').val();
    var city        = $('#ait-register-form-widget input[name=city]').val();
    var zipCode     = $('#ait-register-form-widget input[name=zip_code]').val();
    var countryCode = $('#ait-register-form-widget select[name=country_code]').val();
    var state       = $('#state').val();
    var captcha     = $('#ait-register-form-widget input[name=captcha_validation]').val();

    $('.error').hide();
    $('.success').hide();
    $('#messageError').hide();
    $('#messageInfo').show();


    if(firstName !== undefined && !firstName){
        $('#ait-register-form-widget input[name=first_name]').focus();
        $('#firstNameRegisterErrorEmpty').show();
    }else if(lastName !== undefined && !lastName){
        $('#ait-register-form-widget input[name=last_name]').focus();
        $('#lastNameRegisterErrorEmpty').show();
    }else if(birthDate !== undefined && !birthDate){
        $('#ait-register-form-widget input[name=birth_date]').focus();
        $('#birthDateRegisterErrorEmpty').show();
    }else if(website !== undefined && !website){
        $('#ait-register-form-widget input[name=website]').focus();
        $('#websiteRegisterErrorEmpty').show();
    }else if(company !== undefined && !company){
        $('#ait-register-form-widget input[name=company]').focus();
        $('#companyRegisterErrorEmpty').show();
    }else if(phone !== undefined && !phone){
        $('#ait-register-form-widget input[name=phone]').focus();
        $('#phoneRegisterErrorEmpty').show();
    }else if(fax !== undefined && !fax){
        $('#ait-register-form-widget input[name=fax]').focus();
        $('#faxRegisterErrorEmpty').show();
    }else if(email !== undefined && !email){
        $('#ait-register-form-widget input[name=email]').focus();
        $('#emailRegisterErrorEmpty').show();
    }else if(email && !re.test(email)){
        $('#ait-register-form-widget input[name=email]').focus();
        $('#emailRegisterErrorValid').show();
    }else if(address !== undefined && !address){
        $('#ait-register-form-widget input[name=address]').focus();
        $('#addressRegisterErrorEmpty').show();
    }else if(city !== undefined && !city){
        $('#ait-register-form-widget input[name=city]').focus();
        $('#addressRegisterErrorEmpty').show();
    }else if(zipCode !== undefined && !zipCode){
        $('#ait-register-form-widget input[name=zip_code]').focus();
        $('#zipcodeRegisterErrorEmpty').show();
    }else if(countryCode !== undefined && !countryCode){
        $('#ait-register-form-widget input[name=country_code]').focus();
        $('#countrycodeRegisterErrorEmpty').show();
    }else if(state !== undefined && !state){
        $('#ait-register-form-widget input[name=state]').focus();
        $('#stateRegisterErrorEmpty').show();
    }else if(!username){
        $('#ait-register-form-widget input[name=username]').focus();
        $('#usernameRegisterErrorEmpty').show();
    }else if(!password){
        $('#ait-register-form-widget input[name=password]').focus();
        $('#passwordRegisterErrorEmpty').show();
    }else if(!captcha){
        $('#ait-register-form-widget input[name=captcha_validation]').focus();
        $('#captchaRegisterError').show();
    }else{

        $(el).html($(el).data('sending'));
        $(el).addClass('hover');
        $(el).attr('disabled','disabled');

        $.ajax({
            url: 'customers/registration',
            global: false,
            type: 'POST',
            data: ({
                APPHP_CSRF_TOKEN: token,
                act: "send",
                first_name: firstName,
                last_name: lastName,
                birth_date: birthDate,
                website: website,
                company: company,
                phone: phone,
                fax: fax,
                address: address,
                city: city,
                zip_code: zipCode,
                country_code: countryCode,
                state: state,
                email: email,
                username: username,
                password: password,
                confirm_password: password,
                captcha: captcha,
            }),
            dataType: 'html',
            async: true,
            error: function(html){
                $('#messageInfo').hide();
                $('#messageError').show();
            },
            success: function(html){
                try{
                    var obj = $.parseJSON(html);
                    if(obj.status == '1'){
                        $('.error').hide();
                        $('#ait-register-form-widget input[name=first_name]').val('');
                        $('#ait-register-form-widget input[name=last_name]').val('');
                        $('#ait-register-form-widget input[name=birth_date]').val('');
                        $('#ait-register-form-widget input[name=website]').val('');
                        $('#ait-register-form-widget input[name=company]').val('');
                        $('#ait-register-form-widget input[name=phone]').val('');
                        $('#ait-register-form-widget input[name=fax]').val('');
                        $('#ait-register-form-widget input[name=address]').val('');
                        $('#ait-register-form-widget input[name=zip_code]').val('');
                        //$('#country_code').val('');
                        //$('#state').val('');
                        $('#ait-register-form-widget input[name=email]').val('');
                        $('#ait-register-form-widget input[name=username]').val('');
                        $('#ait-register-form-widget input[name=password]').val('');
                        $('#ait-register-form-widget input[name=captcha_validation]').val('');

                        $('#ait-register-form-widget').slideUp();
                        $('#messageSuccess').show();
                    }else{
                        if(globalDebug){
                            console.error('get ajax question error');
                        }
                        customers_RaiseError(el, obj.error, obj.error_field);
                    }
                }catch(err){
                    if(globalDebug){
                        console.error(err.message);
                    }
                    customers_RaiseError(el, err.message);
                }
            }
        });
    }
    return false;
}

/**
 * Validates form fields and submit the login form
 * @param object el form element
 */
function customers_LoginForm(el)
{
    if(el == null) return false;
    // define this to prevent name overlapping
    var $ = jQuery;

    var username = $('#login_username').val();
    var password = $('#login_password').val();

    $('.alert').hide();
    $('.error').hide();
    $('.success').hide();
    $('#messageError').hide();
    $('#messageInfo').show();

    if(!username){
        $('#login_username').focus();
        $('#usernameErrorEmpty').show();
    }else if(!password){
        $('#login_password').focus();
        $('#passwordErrorEmpty').show();
    }else{
        return true;
    }
    // prevent the default form submission occurring
    return false;
}

/**
 * Raise error message
 * @param el
 * @param errorDescription
 * @param errorField
 */
function customers_RaiseError(el, errorDescription, errorField)
{
    // define this to prevent name overlapping
    var $ = jQuery;

    $('.error').hide();
    $('#messageInfo').hide();
    if(errorDescription !== null) $('#messageError').html(errorDescription);
    if(errorField !== null) $('#'+errorField).focus();
    $('#messageError').show();

    $(el).html($(el).data('send'));
    $(el).removeClass('hover');
    $(el).removeAttr('disabled');
}

/**
 * Change country from dropdown box
 * @param frm
 * @param country
 * @param state
 */
function customers_ChangeCountry(frm, country, state)
{
    // define this to prevent name overlapping
    var $ = jQuery;

    var token = $('#'+frm).find('input[name=APPHP_CSRF_TOKEN]').val();
    var stateId = $('#'+frm).find('*[name=state]').attr('id');
    var countryCode = (country != null) ? country : '';
    var stateCode = (state != null) ? state : '';

    $.ajax({
        url: 'locations/getSubLocations',
        global: false,
        type: 'POST',
        data: ({
            APPHP_CSRF_TOKEN: token,
            act: 'send',
            country_code: countryCode
        }),
        dataType: 'html',
        async: true,
        error: function(html){
            //alert("AJAX: cannot connect to the server or server response error! Please try again later.");
            console.error("AJAX: cannot connect to the server or server response error!");
        },
        success: function(html){
            try{
                var obj = $.parseJSON(html);
                if(obj[0].status == "1"){
                    if(obj.length > 1){
                        $("#"+stateId).replaceWith('<select id="'+stateId+'" name="state"></select>');
                        $("#"+stateId).empty();
                        // add empty option
                        $("<option />", {val: "", text: "--"}).appendTo("#"+stateId);
                        for(var i = 1; i < obj.length; i++){
                            if(obj[i].code == stateCode && stateCode != ''){
                                $("<option />", {val: obj[i].code, text: obj[i].name, selected: true}).appendTo("#"+stateId);
                            }else{
                                $("<option />", {val: obj[i].code, text: obj[i].name}).appendTo("#"+stateId);
                            }
                        }
                    }else{
                        $("#"+stateId).replaceWith('<input type="text" id="'+stateId+'" name="state" data-required="false" maxlength="64" value="'+stateCode+'" />');
                    }
                }else{
                    //alert("An error occurred while receiving data! Please try again later.");
                    if(globalDebug){
                        console.error("An error occurred while receiving data!");
                    }
                }
            }catch(err){
                //alert("An error occurred while receiving data! Please try again later.");
                //console.error("An error occurred while receiving data!");
                if(globalDebug){
                    console.error(err);
                }
            }
        }
    });
}

/**
 * Send server comment
 * @param object el
 * @param int listingId
 */
function reviews_SendComment(el, listingId)
{
    var $ = jQuery;

    if(el == null ||
       isNaN(listingId) ||
       $(el).hasClass('hover')
    ){
        return false;
    }


    var form  = $(el).closest('form');
    var token = form.find('input[name=APPHP_CSRF_TOKEN]').val();
    var re    = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,7})+$/;

    var name                  = $('#rating-name').val();
    var isNameRequired        = $('#rating-name').data('required');
    var email                 = $('#rating-email').val();
    var isEmailRequired       = $('#rating-email').data('required');
    var description           = $('#rating-description').val();
    var isDescriptionRequired = $('#rating-description').data('required');
    var captcha               = form.find('.captcha-result').val();
    var isCaptchaRequired     = $('#reviews_captcha_validation').data('required');
    var ratingPrice    = $('.ratings > div.rating[data-rating-id = 1]').data('rated-value');
    var ratingLocation = $('.ratings > div.rating[data-rating-id = 2]').data('rated-value');
    var ratingStaff    = $('.ratings > div.rating[data-rating-id = 3]').data('rated-value');
    var ratingServices = $('.ratings > div.rating[data-rating-id = 4]').data('rated-value');


    $('.error').hide();
    $('.success').hide();

    if(isNameRequired && !name){
        $('#rating-name').focus();
        $('#reviewsNameError').show();
    }else if(isEmailRequired && !email){
        $('#rating-email').focus();
        $('#reviewsEmailErrorEmpty').show();
    }else if(email && !re.test(email)){
        $('#rating-email').focus();
        $('#reviewsEmailErrorValid').show();
    }else if(isDescriptionRequired && !description){
        $('#rating-description').focus();
        $('#reviewsDescriptionErrorEmpty').show();
    }else if(description.length < 10){
        $('#rating-description').focus();
        $('#reviewsDescriptionErrorValid').show();
    }else if(isCaptchaRequired && !captcha){
        $('#reviews_captcha_validation').focus();
        $('#reviewsCaptchaError').show();
    }else if(ratingPrice == 0 && ratingLocation == 0 && ratingStaff == 0 && ratingServices == 0 && !confirm(jQuery(el).data('text-not-select-rating'))){
        // If confirm return false then function return false
    }else{
        $(el).html($(el).data('sending'));
        $(el).addClass('hover');
        $(el).attr('disabled', 'disabled');

        $.ajax({
            url: 'reviews/sendComment',
            global: false,
            type: 'POST',
            data: ({
                act: 'send',
                APPHP_CSRF_TOKEN: token,
                listingId: listingId,
                name: name,
                email: email,
                description: description,
                ratingPrice: ratingPrice,
                ratingLocation: ratingLocation,
                ratingStaff: ratingStaff,
                ratingServices: ratingServices,
                captcha: captcha
            }),
            dataType: 'html',
            async: true,
            error: function(html){
                $('.error').hide();
                $('#messageError').show();
                $(el).html($(el).data('send'));
                $(el).removeClass('hover');
                $(el).removeAttr('disabled');
            },
            success: function(html){
                try{
                    var obj = $.parseJSON(html);
                    var inputHtml = null;
                    if(obj.status == '1'){
                        $('#rating-name').val('');
                        $('#rating-email').val('');
                        $('#rating-description').val('');
                        $('#reviews_captcha_validation').val('');
                        form.slideUp(600, function(){
                            inputHtml = $.parseHTML(obj.html)
                            inputHtml = $(inputHtml).hide(0);
                            $('.user-ratings').prepend(inputHtml);

                            inputHtml.fadeIn(750);
                        });

                        $('#messageError').hide();
                        $('#messageSuccess').show();
                    }else{
                        reviews_RaiseError(el, obj.error);
                    }
                }catch(err){
                    reviews_RaiseError(el);
                }
            }
        });
    }
    return false;
}

/**
 * Raise error message
 */
function reviews_RaiseError(el, errorDescription)
{
    // define this to prevent name overlapping
    var $ = jQuery;

    $('.error').hide();
    $('#messageInfo').hide();
    if(errorDescription !== null) $('#messageError').html(errorDescription);
    $('#messageError').show();

    $(el).html($(el).data('send'));
    $(el).removeClass('hover');
    $(el).removeAttr('disabled');
}

/**
 * View more comments
 * @param object el
 * @param int listingId
 */
function reviews_viewMoreComments(el, listingId)
{
    if(el == null || listingId == null){
        return false;
    }

    var $ = jQuery;
    var numberReviews = $(".user-ratings > div.user-rating").length;
    var count = 5;

    $.ajax({
        url: "reviews/viewMore",
        global: false,
        type: "POST",
        data: ({
            act: "send",
            listingId: listingId,
            start: numberReviews,
            count: count
        }),
        dataType: "html",
        async: true,
        error: function(html){
            //alert("AJAX: cannot connect to the server or server response error! Please try again later.");
            if(globalDebug){
                console.error("AJAX: cannot connect to the server or server response error!");
            }
        },
        success: function(html){
            try{
                var obj = $.parseJSON(html);
                var changeValues = false;

                if(obj.status == "1"){
                    $(".user-ratings").append(obj.html);
                    if(obj.endReviews == "1"){
                        $("#view-more").fadeOut();
                    }
                }else{
                    if(globalDebug){
                        console.error("Wrong parameters passed or incorrect address! Please try to write this address in a different format.");
                    }
                }
            }catch(err){
                if(globalDebug){
                    console.error("An error occurred while receiving data!");
                    console.error(err);
                }
            }
        }
    });

    return false;
}


function inquiries_getBlockLisitngsHtml(formName, selectCategory, selectLocation, selectSubLocation){
    if(formName == null){
        return null;
    }

    var $ = jQuery;
    var form = $("#"+formName);
    var token = form.find("input[name=APPHP_CSRF_TOKEN]").val();
    var htmlListings = "";
    var infoBlock = "";

    $.ajax({
        url: "AjaxHandler/getLocationsHtml",
        global: false,
        type: "POST",
        data: ({
            APPHP_CSRF_TOKEN: token,
            act: "send",
            category_id: selectCategory,
            region_id: selectLocation,
            subregion_id: selectSubLocation
        }),
        dataType: "html",
        async: true,
        error: function(html){
            //alert("AJAX: cannot connect to the server or server response error! Please try again later.");
            if(globalDebug){
                console.error("AJAX: cannot connect to the server or server response error!");
            }
        },
        success: function(html){
            try{
                var obj = $.parseJSON(html);

                if(obj[0].status == "1"){
                    htmlListings = form.find("fieldset")[2];
                    $(htmlListings).replaceWith(obj[1].content);

                    if(form.find('input[type=submit]').length == 0){
                        var send = form.data('send');
                        $(form.find("fieldset")[1]).after("<div class='row'><input class='button' style='display:none;' value='"+ send +"' name='ap1' type='submit' /></div>");
                        form.append("<div class='buttons-wrapper bw-bottom'><input class='button' style='display:none;' value='"+ send +"' name='ap0' type='submit' /></div>");
                    }

                    if(obj[1].empty){
                        form.find('input[type=submit]').hide();
                    }else{
                        form.find('input[type=submit]').show();
                    }
                }else{
                    console.log("Wrong parameters passed or incorrect!");
                }
            }catch(err){
                //alert("An error occurred while receiving data! Please try again later.");
                if(globalDebug){
                    console.error("An error occurred while receiving data!");
                    console.error(err);
                }
            }
        }
    });

    return false;
}
