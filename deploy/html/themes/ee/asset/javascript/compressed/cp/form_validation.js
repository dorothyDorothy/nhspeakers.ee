/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */
!function(t){t(document).ready(function(){EE.cp.formValidation.init()}),EE.cp.formValidation={paused:!1,_validationCallbacks:[],pause:function(t){if(this.paused=!0,void 0===t){var i=this;setTimeout(function(){i.resume()},3e3)}},resume:function(){this.paused=!1},init:function(i){var i=i||t("form"),e=this;this._textInputSelectors="input[type=text], input[type=number], input[type=password], textarea",i.each(function(i,a){e._bindButtonStateChange(t(a)),e._bindForms(t(a))}),this._focusFirstError(),this._scrollGrid()},bindInputs:function(i){var e=this;t(this._textInputSelectors,i).not("*[data-ajax-validate=no]").blur(function(){t(this).data("validating",!1);var i=t(this);setTimeout(function(){e._sendAjaxRequest(i)},0)}),t(i).on("change","input[type=checkbox], input[type=radio], input[type=hidden], select",function(){var i=t(this);"no"!=i.data("ajaxValidate")&&setTimeout(function(){e._sendAjaxRequest(i)},0)}),t("form.ajax-validate .fieldset-invalid, form.ajax-validate div.grid-publish:has(div.invalid)").each(function(){e._bindTextFieldTimer(t(this))})},bindCallbackForField:function(t,i){this._validationCallbacks[t]=i},_focusFirstError:function(){var i=t(".fieldset-invalid").has(this._textInputSelectors).first().find(this._textInputSelectors).first();if(0!=i.size()){var e=2*i.val().length;i.focus(),i[0].setSelectionRange(e,e)}},_scrollGrid:function(){var i=t(".invalid").has("input, select, textarea").first();if(i.parents(".grid-publish").size()>0){var e=i.position();i.parents(".tbl-wrap").scrollLeft(e.left)}},_bindButtonStateChange:function(i){var e=t(".form-btns input.btn, .form-btns button.btn",i);i.submit(function(a){return e.size()>0&&(e.addClass("work"),e.each(function(e,s){if(a.target==s)return s.prop("disabled",!0),i.append(t("<input/>",{type:"hidden",name:s.name,value:s.value})),!1}),""!=e.data("work-text")&&(e.is("input")?e.attr("value",e.data("work-text")):e.is("button")&&e.text(e.data("work-text")))),!0})},_bindForms:function(i){var e=this;i.each(function(i,a){e.bindInputs(t(this))})},_errorsExist:function(i){return 0!=t(".fieldset-invalid:visible, td.invalid:visible",i).size()},_sendAjaxRequest:function(i){if(!this.paused&&void 0!==i.attr("name")){var e=i.parents("form");if(!e.hasClass("ajax-validate"))return void this._toggleErrorForFields(i,"success");var a=this,s=e.attr("action"),n=e.serialize();t.ajax({url:s,data:n+"&ee_fv_field="+i.attr("name"),type:"POST",dataType:"json",success:function(t){a._toggleErrorForFields(i,t)}})}},markFieldValid:function(t){this._toggleErrorForFields(t,"success")},markFieldInvalid:function(t,i){this._toggleErrorForFields(t,i)},_toggleErrorForFields:function(i,e){var a=i.parents("form"),s=i.parents(".field-control"),n=s.parents("fieldset").size()>0?s.parents("fieldset"):s.parent(),r="em.ee-form-error-message",d=!1,o=i.parents(".tab"),l=o.size()>0?o.attr("class").match(/t-\d+/):"",u=t(o).parents(".tab-wrap").find('a[rel="'+l+'"]'),f=o.size()>0&&o.find(".form-btns input.btn").size()>0,c=f?o.find(".form-btns .btn"):a.find(".form-btns .btn");if(n.hasClass("fieldset-faux")&&(n=n.find("div.field-instruct"),s=i.parents("td"),d=!0),"success"==e)d?(s.removeClass("fieldset-invalid"),s.removeClass("invalid"),0==n.parent().find("td.invalid").size()&&(n.removeClass("fieldset-invalid"),s.parents("div.field-control").find("> "+r).remove())):n.removeClass("fieldset-invalid"),s.find("> "+r).remove(),u.size()>0&&!this._errorsExist(o)&&u.removeClass("invalid"),(!this._errorsExist(a)||!this._errorsExist(o)&&f)&&(c.removeClass("disable").removeAttr("disabled"),c.each(function(i,e){e=t(e),e.is("input")?e.attr("value",e.data("submit-text")):e.is("button")&&e.text(e.data("submit-text"))}));else{this._bindTextFieldTimer(s),n.addClass("fieldset-invalid"),d&&s.addClass("invalid");var v=t("<div/>").html(e.error).contents();s.has(r).length&&s.find(r).remove(),s.append(v),u.size()>0&&u.addClass("invalid"),c.addClass("disable").attr("disabled","disabled"),c.is("input")?c.attr("value",EE.lang.btn_fix_errors):c.is("button")&&c.text(EE.lang.btn_fix_errors)}var p=i.attr("name").replace(/\[.+?\]/g,"");void 0!==this._validationCallbacks[p]&&this._validationCallbacks[p]("success"==e,e.error,i)},_bindTextFieldTimer:function(i){var e,a=this,s=t(this._textInputSelectors,i);s.data("validating")!==!0&&s.data("validating",!0).on("keydown change",function(){void 0!==e&&clearTimeout(e);var i=t(this);e=setTimeout(function(){clearTimeout(e),a._sendAjaxRequest(i)},500)})}}}(jQuery);