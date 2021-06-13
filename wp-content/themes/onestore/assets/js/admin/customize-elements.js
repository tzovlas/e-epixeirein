(function ($) {
    "use strict";

    window.onestore_media_open = false;
    $.fn.OneStoreElement_Media = function (control) {


        this.on("click", ".css-type-media .media-remove", function (e) {
            e.preventDefault();
            var $button = $(this);
            var $wrapper = $button.closest(".css-type-media");

            $wrapper.find("input.media-value").val("").trigger("change");
            $wrapper.removeClass("has-media");
            $wrapper.find(".media-preview").html("");

            return false;
        });
       
        return this.find( '.css-type-media .upload-button' ).each(function () {
            var $el = $(this);
            var frame;
            $el.on("click", function (event) {
                event.preventDefault();

                var $button = $(this);
                var $wrapper = $button.closest(".css-type-media");

                // If the media frame already exists, reopen it.
                if (frame) {
                    frame.open();
                    return;
                }

                // Create the media frame.
                frame = wp.media({});

               

                // When an image is selected, run a callback.
                frame.on("select", function () {
                    var attachment = frame
                        .state()
                        .get("selection")
                        .first()
                        .toJSON();

                    $wrapper
                        .find("input.media-value")
                        .val(attachment.url)
                        .trigger("change");
                    $wrapper.addClass("has-media");
                    let $image = $("<img/>");
                    $image.attr("src", attachment.url);
                    $wrapper.find(".media-preview").html($image);
                });

                frame.on("open", function () { 
                    window.onestore_media_open = true;
                } );

                frame.on("close", function () { 
                    setTimeout( function(){
                        window.onestore_media_open  = false;
                    }, 500 );
                } );


                frame.open();


            });
        });
    };

    $.fn.OneStoreElement_Color = function (control) {
        return this.each(function () {
            var $el = $(this);
            var $pickers = $el.find(".color-picker");
            $pickers.each(function () {
                var $picker = $(this);
                let p = $picker.parent();
                let $valInput = p.find(".color-picker-val");
                $picker.alphaColorPicker({
                    change: function () {
                        let color = $picker.wpColorPicker("color");
                        if (control) {
                            control.setting.set(color);
                        }
                        if ($valInput.length) {
                            $valInput.val(color).trigger("change");
                        }
                    },
                    clear: function () {
                        if (control) {
                            control.setting.set("");
                        }

                        if ($valInput.length) {
                            $valInput.val("").trigger("change");
                        }
                    },
                });
            });
        });
    };

    $.fn.OneStoreElement_Dimension = function (control) {
        return this.each(function () {
            var $wrapper = $(this);
            var $items = $wrapper.find(".onestore-dimension-fieldset");
            $items.each(function () {
                var $el = $(this);
                $el.each(function (i, el) {
                    var $el = $(el),
                        $unit = $el.find(".onestore-dimension-unit"),
                        $input = $el.find(".onestore-dimension-input"),
                        $value = $el.find(".onestore-dimension-value");

                    $unit.on("change", function (e) {
                        var $option = $unit.find(
                            'option[value="' + this.value + '"]'
                        );

                        $input.attr("min", $option.attr("data-min"));
                        $input.attr("max", $option.attr("data-max"));
                        $input.attr("step", $option.attr("data-step"));

                        $input.val("").trigger("change");
                    });

                    $input.on("change blur", function (e) {
                        var value =
                            "" === this.value
                                ? ""
                                : this.value.toString() +
                                  $unit.val().toString();

                        $value.val(value).trigger("change");
                    });
                });
            });
        }); // end plugin each.
    };

    $.fn.OneStoreElement_Dimensions = function (control) {
        return this.each(function () {
            var $wrapper = $(this);
            var $items = $wrapper.find(".onestore-dimensions-fieldset");
            $items.each(function () {
                $(this).each(function (i, el) {
                    var $el = $(el),
                        $unit = $el.find(".onestore-dimensions-unit"),
                        $link = $el.find(".onestore-dimensions-link"),
                        $unlink = $el.find(".onestore-dimensions-unlink"),
                        $inputs = $el.find(".onestore-dimensions-input"),
                        $value = $el.find(".onestore-dimensions-value");
                        var allow_auto =  $value.attr( 'data-empty-auto' ) === 'true';
               
                    $unit.on("change", function (e) {
                        var $option = $unit.find(
                            'option[value="' + this.value + '"]'
                        );

                        $inputs.attr("min", $option.attr("data-min"));
                        $inputs.attr("max", $option.attr("data-max"));
                        $inputs.attr("step", $option.attr("data-step"));

                        $inputs.val("").trigger("change");
                    });

                    $link.on("click", function (e) {
                        e.preventDefault();

                        $el.attr("data-linked", "true");
                        $inputs.val($inputs.first().val()).trigger("change");
                        $inputs.first().focus();
                    });

                    $unlink.on("click", function (e) {
                        e.preventDefault();

                        $el.attr("data-linked", "false");
                        $inputs.first().focus();
                    });

                    $inputs.on("keyup mouseup", function (e) {
                        if ("true" == $el.attr("data-linked")) {
                            $inputs.not(this).val(this.value).trigger("change");
                        }
                    });

                    $inputs.on("change blur", function (e) {
                        var values = [],
                            unit = $unit.val().toString(),
                            isEmpty = true,
                            value;

                        $inputs.each(function () {
                            if ( ! this.value.length ) {
                                if ( allow_auto ) {
                                    values.push("_");
                                } else {
                                    values.push( '0' );
                                }
                            } else {
                                values.push(this.value.toString() + unit);
                                isEmpty = false;
                            }
                        });

                        if (isEmpty) {
                            value = "   ";
                        } else {
                            value = values.join(" ");
                            value = value.replace(/_/ig, 'auto' );
                        }

                        $value.val(value).trigger("change");
                    });
                });
            });
        }); // end plugin each.
    };

    $.fn.OneStoreElement_Slider = function (control) {
        return this.each(function () {
            var $wrapper = $(this);
            var $items = $wrapper.find(".onestore-slider-fieldset");
            $items.each(function () {
                var $el = $(this),
                    $unit = $el.find(".onestore-slider-unit"),
                    $input = $el.find(".onestore-slider-input"),
                    $slider = $el.find(".onestore-slider-ui"),
                    $reset = $el.find(".onestore-slider-reset"),
                    $value = $el.find(".onestore-slider-value");

                $slider.slider({
                    value: $input.val(),
                    min: +$input.attr("min"),
                    max: +$input.attr("max"),
                    step: +$input.attr("step"),
                    slide: function (e, ui) {
                        $input.val(ui.value).trigger("change");
                    },
                });

                $reset.on("click", function (e) {
                    var resetNumber = $(this).attr("data-number"),
                        resetUnit = $(this).attr("data-unit");

                    $unit.val(resetUnit);
                    $input.val(resetNumber).trigger("change");
                    $slider.slider("value", resetNumber);
                });

                $unit.on("change", function (e) {
                    var $option = $unit.find(
                        'option[value="' + this.value + '"]'
                    );

                    $input.attr("min", $option.attr("data-min") || false);
                    $input.attr("max", $option.attr("data-max") || false);
                    $input.attr("step", $option.attr("data-step") || false);

                    $slider.slider("option", {
                        min: +$input.attr("min"),
                        max: +$input.attr("max"),
                        step: +$input.attr("step"),
                    });

                    $input.val("").trigger("change");
                });

                $input.on("change blur", function (e) {
                    $slider.slider("value", this.value);
                    var value = "";
                    if ($unit.length) {
                        value =
                            "" === this.value
                                ? ""
                                : this.value.toString() +
                                  $unit.val().toString();
                    } else {
                        value = "" === this.value ? "" : this.value.toString();
                    }

                    $value.val(value).trigger("change");
                });
            }); // end loop items.
        }); // end plugin each.
    };

    $.fn.OneStoreElement_Typo = function (control) {
        return this.each(function () {
            var $wrapper = $(this);
            var $items = $wrapper.find(".onestore-typography-fieldset > .onestore-row-item");
            $items.each(function () {
                var $el = $(this),
                    $unit = $el.find(".onestore-typography-size-unit"),
                    $input = $el.find(".onestore-typography-size-input"),
                    $value = $el.find(".onestore-typography-size-value");

                var setNumberAttrs = function (unit) {
                    var $option = $unit.find('option[value="' + unit + '"]');

                    $input.attr("min", $option.attr("data-min") || '');
                    $input.attr("max", $option.attr("data-max") || '');
                    $input.attr("step", $option.attr("data-step") || '');
                };

                $unit.on("change", function (e) {
                    setNumberAttrs(this.value);
                    $input.val("").trigger("change");
                });

                setNumberAttrs($unit.val());

                $input.on("change blur", function (e) {
                    var value =
                        "" === this.value
                            ? ""
                            : this.value.toString() + $unit.val().toString();

                    console.log( 'Typo_value', value );

                    $value.val(value).trigger("change");
                });
            });
        }); // end plugin each.
    };

    $.fn.OneStoreElement_Shadow = function (control) {
        return this.each(function () {
            var $wrapper = $(this);
            var $items = $wrapper.find(".onestore-shadow-fieldset");
            $items.each(function () {
                var $el = $(this),
                    $inputs = $el.find(".onestore-shadow-input"),
                    $value = $el.find(".onestore-shadow-value");

                var updateValue = function (e) {
                    var values = $inputs
                        .map(function () {
                            return $(this).hasClass("color-picker")
                                ? "" === $(this).wpColorPicker("color")
                                    ? "rgba(0,0,0,0)"
                                    : $(this).wpColorPicker("color")
                                : "" === this.value
                                ? "0"
                                : this.value.toString() + "px";
                        })
                        .get();

                    $value.val(values.join(" ")).trigger("change");
                };

                $el.find(
                    ".onestore-shadow-color .color-picker"
                ).alphaColorPicker({
                    change: updateValue,
                    clear: updateValue,
                });

                $el.on("change blur", ".onestore-shadow-input", updateValue);
            });
        }); // end plugin each.
    };

    $.fn.OneStoreElement______ = function (control) {
        return this.each(function () {
            var $wrapper = $(this);
            var $items = $wrapper.find(".onestore-dimension-fieldset");
            $items.each(function () {});
        }); // end plugin each.
    };
})(jQuery);
