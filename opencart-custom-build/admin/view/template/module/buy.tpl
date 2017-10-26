<?php

echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-buy" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-buy" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <li><a href="#tab-form-fields" data-toggle="tab"><?php echo $tab_form_fields; ?></a></li>
                        <li><a href="#tab-headings" data-toggle="tab"><?php echo $tab_headings; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <div class="row-fluid clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                        <div class="col-sm-8">
                                            <select name="buy_status" id="input-status" class="form-control">
                                                <option value="0"<?php if($buy_status == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                <option value="1"<?php if($buy_status == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-min_order_sum"><?php echo $entry_min_order_sum; ?></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="buy_min_order_sum" value="<?php echo (isset($settings['buy_min_order_sum'])?$settings['buy_min_order_sum']:'');?>" style="width: 85px;" class="form-control" id="input-min_order_sum" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-cart_modules"><?php echo $entry_cart_modules; ?></label>
                                        <div class="col-sm-8">
                                            <select name="buy_cart_modules" class="form-control" id="input-cart_modules">
                                                <option value="0"<?php if(!isset($settings['buy_cart_modules']) || $settings['buy_cart_modules'] == 0){?> selected="selected"<?php }?>><?php echo $text_no;?></option>
                                                <option value="1"<?php if(isset($settings['buy_cart_modules']) && $settings['buy_cart_modules'] == 1){?> selected="selected"<?php }?>><?php echo $text_yes;?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-form_design"><?php echo $entry_form_design; ?></label>
                                        <div class="col-sm-8">
                                            <select name="buy_form_design" class="form-control" id="input-form_design">
                                                <option value="0"<?php if(!isset($settings['buy_form_design']) || $settings['buy_form_design'] == 0){?> selected="selected"<?php }?>><?php echo $text_two_col;?></option>
                                                <option value="1"<?php if(isset($settings['buy_form_design']) && $settings['buy_form_design'] == 1){?> selected="selected"<?php }?>><?php echo $text_single_col;?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-shipping_select"><?php echo $entry_shipping_select; ?></label>
                                        <div class="col-sm-8">
                                            <select name="buy_shipping_select" class="form-control" id="input-shipping_select">
                                                <option value="0"<?php if(!isset($settings['buy_shipping_select']) || $settings['buy_shipping_select'] == 0){?> selected="selected"<?php }?>><?php echo $text_no;?></option>
                                                <option value="1"<?php if(isset($settings['buy_shipping_select']) && $settings['buy_shipping_select'] == 1){?> selected="selected"<?php }?>><?php echo $text_yes;?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-payment_select"><?php echo $entry_payment_select; ?></label>
                                        <div class="col-sm-8">
                                            <select name="buy_payment_select" class="form-control" id="input-payment_select">
                                                <option value="0"<?php if(!isset($settings['buy_payment_select']) || $settings['buy_payment_select'] == 0){?> selected="selected"<?php }?>><?php echo $text_no;?></option>
                                                <option value="1"<?php if(isset($settings['buy_payment_select']) && $settings['buy_payment_select'] == 1){?> selected="selected"<?php }?>><?php echo $text_yes;?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-telephone_mask"><?php echo $entry_telephone_mask; ?></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="buy_telephone_mask" value="<?php echo (isset($settings['buy_telephone_mask'])?$settings['buy_telephone_mask']:'');?>" class="form-control" id="input-telephone_mask" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab-form-fields">

                            <div class="row-fluid">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_firstname; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_firstname_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_firstname_status']) && $settings['buy_firstname_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_firstname_status']) && $settings['buy_firstname_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_firstname_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_firstname_required']) || $settings['buy_firstname_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_firstname_required']) && $settings['buy_firstname_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_lastname; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_lastname_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_lastname_status']) && $settings['buy_lastname_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_lastname_status']) && $settings['buy_lastname_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_lastname_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_lastname_required']) || $settings['buy_lastname_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_lastname_required']) && $settings['buy_lastname_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_email; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_email_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_email_status']) && $settings['buy_email_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_email_status']) && $settings['buy_email_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_email_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_email_required']) || $settings['buy_email_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_email_required']) && $settings['buy_email_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_telephone; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_telephone_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_telephone_status']) && $settings['buy_telephone_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_telephone_status']) && $settings['buy_telephone_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_telephone_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_telephone_required']) || $settings['buy_telephone_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_telephone_required']) && $settings['buy_telephone_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_fax; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_fax_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_fax_status']) && $settings['buy_fax_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_fax_status']) && $settings['buy_fax_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_fax_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_fax_required']) || $settings['buy_fax_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_fax_required']) && $settings['buy_fax_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_company; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_company_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_company_status']) && $settings['buy_company_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_company_status']) && $settings['buy_company_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_company_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_company_required']) || $settings['buy_company_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_company_required']) && $settings['buy_company_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_address_1; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_address_1_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_address_1_status']) && $settings['buy_address_1_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_address_1_status']) && $settings['buy_address_1_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_address_1_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_address_1_required']) || $settings['buy_address_1_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_address_1_required']) && $settings['buy_address_1_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_address_2; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_address_2_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_address_2_status']) && $settings['buy_address_2_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_address_2_status']) && $settings['buy_address_2_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_address_2_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_address_2_required']) || $settings['buy_address_2_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_address_2_required']) && $settings['buy_address_2_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_zone; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_zone_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_zone_status']) && $settings['buy_zone_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_zone_status']) && $settings['buy_zone_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_zone_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_zone_required']) || $settings['buy_zone_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_zone_required']) && $settings['buy_zone_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_city; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_city_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_city_status']) && $settings['buy_city_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_city_status']) && $settings['buy_city_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_city_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_city_required']) || $settings['buy_city_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_city_required']) && $settings['buy_city_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_postcode; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_postcode_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_postcode_status']) && $settings['buy_postcode_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_postcode_status']) && $settings['buy_postcode_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_postcode_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_postcode_required']) || $settings['buy_postcode_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_postcode_required']) && $settings['buy_postcode_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_country; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_country_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_country_status']) && $settings['buy_country_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_country_status']) && $settings['buy_country_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_country_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_country_required']) || $settings['buy_country_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_country_required']) && $settings['buy_country_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_comment; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="buy_comment_status" class="status form-control">
                                                    <option value="0"<?php if(isset($settings['buy_comment_status']) && $settings['buy_comment_status'] == 0){?> selected="selected"<?php }?>><?php echo $text_disabled;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_comment_status']) && $settings['buy_comment_status'] == 1){?> selected="selected"<?php }?>><?php echo $text_enabled;?></option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="buy_comment_required" class="form-control">
                                                    <option value="0"<?php if(!isset($settings['buy_comment_required']) || $settings['buy_comment_required'] == 0){?> selected="selected"<?php }?>><?php echo $text_not_required;?></option>
                                                    <option value="1"<?php if(isset($settings['buy_comment_required']) && $settings['buy_comment_required'] == 1){?> selected="selected"<?php }?>><?php echo $text_required;?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-headings">
                            <ul class="nav nav-tabs" id="language">
                                <?php foreach ($languages as $language) { ?>
                                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                <?php } ?>
                              </ul>
                            <div class="tab-content">
                                <?php foreach ($languages as $language) { ?>
                            <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                            <div class="row-fluid clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-meta_title_<?php echo $language['code']; ?>"><?php echo $entry_meta_title; ?></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="buy_meta_title_<?php echo $language['code']; ?>" value="<?php echo (isset($settings['buy_meta_title_'.$language['code']])?$settings['buy_meta_title_'.$language['code']]:$text_meta_title);?>" class="form-control" id="input-meta_title_<?php echo $language['code']; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-h1_<?php echo $language['code']; ?>"><?php echo $entry_h1; ?></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="buy_h1_<?php echo $language['code']; ?>" value="<?php echo (isset($settings['buy_h1_'.$language['code']])?$settings['buy_h1_'.$language['code']]:$text_h1);?>" class="form-control" id="input-h1_<?php echo $language['code']; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-h2_<?php echo $language['code']; ?>"><?php echo $entry_h2; ?></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="buy_h2_<?php echo $language['code']; ?>" value="<?php echo (isset($settings['buy_h2_'.$language['code']])?$settings['buy_h2_'.$language['code']]:$text_h2);?>" class="form-control" id="input-h2_<?php echo $language['code']; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-form_headings"><?php echo $entry_form_headings; ?></label>
                                        <div class="col-sm-8">
                                            <select name="buy_form_headings" class="form-control" id="input-form_headings">
                                                <option value="0"<?php if(!isset($settings['buy_form_headings']) || $settings['buy_form_headings'] == 0){?> selected="selected"<?php }?>><?php echo $text_no;?></option>
                                                <option value="1"<?php if(isset($settings['buy_form_headings']) && $settings['buy_form_headings'] == 1){?> selected="selected"<?php }?>><?php echo $text_yes;?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-heading_1_<?php echo $language['code']; ?>"><?php echo $entry_heading_1; ?></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="buy_heading_1_<?php echo $language['code']; ?>" value="<?php echo (isset($settings['buy_heading_1_'.$language['code']])?$settings['buy_heading_1_'.$language['code']]:$text_heading_1);?>" class="form-control" id="input-heading_1_<?php echo $language['code']; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-heading_2_<?php echo $language['code']; ?>"><?php echo $entry_heading_2; ?></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="buy_heading_2_<?php echo $language['code']; ?>" value="<?php echo (isset($settings['buy_heading_2_'.$language['code']])?$settings['buy_heading_2_'.$language['code']]:$text_heading_2);?>" class="form-control" id="input-heading_2_<?php echo $language['code']; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-heading_3_<?php echo $language['code']; ?>"><?php echo $entry_heading_3; ?></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="buy_heading_3_<?php echo $language['code']; ?>" value="<?php echo (isset($settings['buy_heading_3_'.$language['code']])?$settings['buy_heading_3_'.$language['code']]:$text_heading_3);?>" class="form-control" id="input-heading_3_<?php echo $language['code']; ?>" />
                                    </div>
                                </div>
                                </div>
                                
                            </div>
                            </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('.status').change(function () {
    var name = (this.name).split('_');
    if (this.value == 0) {
        if (name[1] == 'address')
            name[1] = name[1] + '_' + name[2];
        $('select[name=\'buy_' + name[1] + '_required\']').val('0');
    }

});
$('#language a:first').tab('show');
</script>
<?php echo $footer; ?>