// Login account selection verification
function check_account_type()
{
	var account_type	=	document.getElementById('account_selector').value;
	if (account_type == "") {
		Growl.info({title:"Please select an account type first",text:" "})
		return false;
	}
	else
		return true;
}


// Login page image swiping starts//
$(document).ready(function(){
  $("#account_selector").change(function(){
	  //squeezee in
	  function rotate_in()
	  {
			$('#avatar').toggleClass('flip_in');
	  }
	  setTimeout(rotate_in, 0);
	  //change img src
	  function set_img()
	  {
		  var img = document.getElementById('account_selector').value;
		  if(img == "")
				img	=	'account';
		  $("#account_img").attr("src", "template/images/icons_big/"+img+".png");
	  }
	  setTimeout(set_img, 200);
	  //expand out
	  function rotate_out()
	  {
			$('#avatar').toggleClass('flip_out');
	  }
	  setTimeout(rotate_out, 200);
	  //clear css
	  function reset_class()
	  {
			$("#avatar").attr("class", "avatar");
	  }
	  setTimeout(reset_class, 500);
  });
});
// Login page image swiping ends//

$(function () {
    $("a[href^=#]").click(function (e) {
        e.preventDefault()
    });
    /*$("html").niceScroll({
        zindex: 999
    });*/
    $(".nice-scroll").niceScroll({
        railoffset: {
            left: -3
        }
    });
    $(".show-tooltip").tooltip({
        container: "body",
        delay: {
            show: 500
        }
    });
    $(".show-popover").popover();
    window.prettyPrint && prettyPrint();
    $("#sidebar a.dropdown-toggle").click(function () {
        var e = $(this).next(".submenu");
        var t = $(this).children(".arrow");
        e.slideToggle(400, function () {
            if ($(this).is(":hidden")) {
                t.attr("class", "arrow icon-angle-right")
            } else {
                t.attr("class", "arrow icon-angle-down")
            }
        })
    });
    $("#sidebar.sidebar-collapsed #sidebar-collapse > i").attr("class", "icon-double-angle-right");
    $("#sidebar-collapse").click(function () {
        $("#sidebar").toggleClass("sidebar-collapsed");
        if ($("#sidebar").hasClass("sidebar-collapsed")) {
            $("#sidebar-collapse > i").attr("class", "icon-double-angle-right")
        } else {
            $("#sidebar-collapse > i").attr("class", "icon-double-angle-left")
        }
        $(".nice-scroll").getNiceScroll().resize()
    });
    $("#sidebar .search-form").click(function () {
        $('#sidebar .search-form input[type="text"]').focus()
    });
    $("#sidebar .nav > li.active > a > .arrow").removeClass("icon-angle-right").addClass("icon-angle-down");
    $("#theme-setting > a").click(function () {
        $(this).next().animate({
            width: "toggle"
        }, 500, function () {
            if ($(this).is(":hidden")) {
                $("#theme-setting > a > i").attr("class", "icon-gears icon-2x")
            } else {
                $("#theme-setting > a > i").attr("class", "icon-remove icon-2x")
            }
        });
        $(this).next().css("display", "inline-block")
    });
    $("#theme-setting ul.colors a").click(function () {
        var e = $(this).parent().get(0);
        var t = $(e).parent().get(0);
        var n = $(t).data("target");
        var r = $(t).data("prefix");
        var i = $(this).attr("class");
        var s = new RegExp("\\b" + r + ".*\\b", "g");
        $(t).children("li").removeClass("active");
        $(e).addClass("active");
        if ($(n).attr("class") != undefined) {
            $(n).attr("class", $(n).attr("class").replace(s, "").trim())
        }
        $(n).addClass(r + i);
        if (n == "body") {
            var o = $(t).parent().get(0);
            var u = $(o).nextAll("li:lt(2)");
            $(u).find("li.active").removeClass("active");
            $(u).find("a." + i).parent().addClass("active");
            $("#navbar").attr("class", $("#navbar").attr("class").replace(/\bnavbar-.*\b/g, "").trim());
            $("#main-container").attr("class", $("#main-container").attr("class").replace(/\bsidebar-.*\b/g, "").trim())
        }
    });
    if ($("#sidebar").hasClass("sidebar-fixed")) {
        $('#theme-setting > ul > li > a[data-target="sidebar"] > i').attr("class", "icon-check green")
    }
    if ($("#navbar").hasClass("navbar-fixed")) {
        $('#theme-setting > ul > li > a[data-target="navbar"] > i').attr("class", "icon-check green")
    }
    $("#theme-setting > ul > li > a").click(function () {
        var e = $(this).data("target");
        var t = $(this).children("i");
        if (t.hasClass("icon-check-empty")) {
            t.attr("class", "icon-check green");
            $("#" + e).addClass(e + "-fixed")
        } else {
            t.attr("class", "icon-check-empty");
            $("#" + e).removeClass(e + "-fixed")
        }
    });
    $(".box .box-tool > a").click(function (e) {
        if ($(this).data("action") == undefined) {
            return
        }
        var t = $(this).data("action");
        var n = $(this);
        switch (t) {
        case "collapse":
            $(this).parents(".box").children(".box-content").slideToggle(500, function () {
                if ($(this).is(":hidden")) {
                    $(n).children("i").attr("class", "icon-chevron-down")
                } else {
                    $(n).children("i").attr("class", "icon-chevron-up")
                }
            });
            break;
        case "close":
            $(this).parents(".box").fadeOut(500, function () {
                $(this).parent().remove()
            });
            break;
        case "config":
            $("#" + $(this).data("modal")).modal("show");
            break
        }
        e.preventDefault()
    });
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $("#btn-scrollup").fadeIn()
        } else {
            $("#btn-scrollup").fadeOut()
        }
    });
    $("#btn-scrollup").click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false
    });
    $("#gritter-sticky").click(function () {
        var e = $.gritter.add({
            title: "This is a sticky notice!",
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
            image: "./img/demo/avatar/avatar1.jpg",
            sticky: true,
            time: "",
            class_name: "my-sticky-class"
        });
        return false
    });
    $("#gritter-regular").click(function () {
        $.gritter.add({
            title: "This is a regular notice!",
            text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
            image: "./img/demo/avatar/avatar1.jpg",
            sticky: false,
            time: ""
        });
        return false
    });
    $("#gritter-max").click(function () {
        $.gritter.add({
            title: "This is a notice with a max of 3 on screen at one time!",
            text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
            image: "./img/demo/avatar/avatar1.jpg",
            sticky: false,
            before_open: function () {
                if ($(".gritter-item-wrapper").length == 3) {
                    return false
                }
            }
        });
        return false
    });
    $("#gritter-without-image").click(function () {
        $.gritter.add({
            title: "This is a notice without an image!",
            text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.'
        });
        return false
    });
    $("#gritter-light").click(function () {
        $.gritter.add({
            title: "This is a light notification",
            text: 'Just add a "gritter-light" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
            class_name: "gritter-light"
        });
        return false
    });
    $("#gritter-remove-all").click(function () {
        $.gritter.removeAll();
        return false
    });
    if (jQuery().slider) {
        $(".slider-basic").slider();
        $("#slider-snap-inc").slider({
            value: 100,
            min: 0,
            max: 1e3,
            step: 100,
            slide: function (e, t) {
                $("#slider-snap-inc-amount").text("$" + t.value)
            }
        });
        $("#slider-snap-inc-amount").text("$" + $("#slider-snap-inc").slider("value"));
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 500,
            values: [75, 300],
            slide: function (e, t) {
                $("#slider-range-amount").text("$" + t.values[0] + " - $" + t.values[1])
            }
        });
        $("#slider-range-amount").text("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
        $("#slider-range-max").slider({
            range: "max",
            min: 1,
            max: 10,
            value: 2,
            slide: function (e, t) {
                $("#slider-range-max-amount").text(t.value)
            }
        });
        $("#slider-range-max-amount").text($("#slider-range-max").slider("value"));
        $("#slider-range-min").slider({
            range: "min",
            value: 37,
            min: 1,
            max: 700,
            slide: function (e, t) {
                $("#slider-range-min-amount").text("$" + t.value)
            }
        });
        $("#slider-range-min-amount").text("$" + $("#slider-range-min").slider("value"));
        $("#slider-eq > span").each(function () {
            var e = parseInt($(this).text(), 10);
            $(this).empty().slider({
                value: e,
                range: "min",
                animate: true,
                orientation: "vertical"
            })
        });
        $("#slider-vertical").slider({
            orientation: "vertical",
            range: "min",
            min: 0,
            max: 100,
            value: 60,
            slide: function (e, t) {
                $("#slider-vertical-amount").text(t.value)
            }
        });
        $("#slider-vertical-amount").text($("#slider-vertical").slider("value"));
        $("#slider-range-vertical").slider({
            orientation: "vertical",
            range: true,
            values: [17, 67],
            slide: function (e, t) {
                $("#slider-range-vertical-amount").text("$" + t.values[0] + " - $" + t.values[1])
            }
        });
        $("#slider-range-vertical-amount").text("$" + $("#slider-range-vertical").slider("values", 0) + " - $" + $("#slider-range-vertical").slider("values", 1));
        $(".slider-color-preview").slider({
            range: "min",
            value: 106,
            min: 1,
            max: 700
        })
    }
    if (jQuery().knob) {
        $(".knob").knob({
            dynamicDraw: true,
            thickness: .2,
            tickColorizeValues: true,
            skin: "tron"
        });
        $(".circle-stats-item > input").knob({
            readOnly: true,
            width: 120,
            height: 120,
            dynamicDraw: true,
            thickness: .2,
            tickColorizeValues: true,
            skin: "tron"
        })
    }
    $('.table > thead > tr > th:first-child > input[type="checkbox"]').change(function () {
        var e = false;
        if ($(this).is(":checked")) {
            e = true
        }
        $(this).parents("thead").next().find('tr > td:first-child > input[type="checkbox"]').prop("checked", e)
    });
    if (jQuery().dataTable) {
        $(".dTable").dataTable({
            aLengthMenu: [
                [10, 15, 25, 50, 100, -1],
                [10, 15, 25, 50, 100, "All"]
            ],
            iDisplayLength: 10,
            oLanguage: {
                sLengthMenu: "_MENU_ Records per page",
                sInfo: "_START_ - _END_ of _TOTAL_",
                sInfoEmpty: "0 - 0 of 0",
                oPaginate: {
                    sPrevious: "Prev",
                    sNext: "Next"
                }
            },
            aoColumnDefs: [{
                bSortable: false,
                aTargets: [0]
            }]
        })
    }
    if (jQuery().chosen) {
        $(".chosen").chosen();
        $(".chosen-with-diselect").chosen({
            allow_single_deselect: true
        })
    }
    if (jQuery().tagsInput) {
        $("#tag-input-1").tagsInput({
            width: "auto",
            onAddTag: function (e) {
                alert("New tag added: " + e)
            }
        });
        $("#tag-input-2").tagsInput({
            width: 240
        })
    }
    if (jQuery().colorpicker) {
        $(".colorpicker-default").colorpicker({
            format: "hex"
        });
        $(".colorpicker-rgba").colorpicker()
    }
    if (jQuery().timepicker) {
        $(".timepicker-default").timepicker();
        $(".timepicker-24").timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false
        })
    }
    if (jQuery().datepicker) {
        $(".datepicker").datepicker()
    }
    if (jQuery().daterangepicker) {
        $(".date-range").daterangepicker();
        $("#form-date-range").daterangepicker({
            ranges: {
                Today: ["today", "today"],
                Yesterday: ["yesterday", "yesterday"],
                "Last 7 Days": [Date.today().add({
                    days: -6
                }), "today"],
                "Last 30 Days": [Date.today().add({
                    days: -29
                }), "today"],
                "This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                "Last Month": [Date.today().moveToFirstDayOfMonth().add({
                    months: -1
                }), Date.today().moveToFirstDayOfMonth().add({
                    days: -1
                })]
            },
            opens: "right",
            format: "MM/dd/yyyy",
            separator: " to ",
            startDate: Date.today().add({
                days: -29
            }),
            endDate: Date.today(),
            minDate: "01/01/2012",
            maxDate: "12/31/2014",
            locale: {
                applyLabel: "Submit",
                fromLabel: "From",
                toLabel: "To",
                customRangeLabel: "Custom Range",
                daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                firstDay: 1
            },
            showWeekNumbers: true,
            buttonClasses: ["btn-danger"]
        }, function (e, t) {
            $("#form-date-range span").html(e.toString("MMMM d, yyyy") + " - " + t.toString("MMMM d, yyyy"))
        });
        $("#form-date-range span").html(Date.today().add({
            days: -29
        }).toString("MMMM d, yyyy") + " - " + Date.today().toString("MMMM d, yyyy"))
    }
    if (jQuery().clockface) {
        $("#clockface_1").clockface();
        $("#clockface_2").clockface({
            format: "HH:mm",
            trigger: "manual"
        });
        $("#clockface_2_toggle-btn").click(function (e) {
            e.stopPropagation();
            $("#clockface_2").clockface("toggle")
        });
        $("#clockface_3").clockface({
            format: "H:mm"
        }).clockface("show", "14:30")
    }
    if (jQuery().wysihtml5) {
        $(".wysihtml5").wysihtml5()
    }
    if (jQuery().bootstrapWizard) {
        $("#form-wizard-1").bootstrapWizard({
            nextSelector: ".button-next",
            previousSelector: ".button-previous",
            onTabClick: function (e, t, n) {
                alert("on tab click disabled");
                return false
            },
            onNext: function (e, t, n) {
                var r = t.find("li").length;
                var i = n + 1;
                $(".step-title", $("#form-wizard-1")).text("Step " + (n + 1) + " of " + r);
                jQuery("li", $("#form-wizard-1")).removeClass("done");
                var s = t.find("li");
                for (var o = 0; o < n; o++) {
                    jQuery(s[o]).addClass("done")
                }
                if (i == 1) {
                    $("#form-wizard-1").find(".button-previous").hide()
                } else {
                    $("#form-wizard-1").find(".button-previous").show()
                } if (i >= r) {
                    $("#form-wizard-1").find(".button-next").hide();
                    $("#form-wizard-1").find(".button-submit").show()
                } else {
                    $("#form-wizard-1").find(".button-next").show();
                    $("#form-wizard-1").find(".button-submit").hide()
                }
                $("html, body").animate({
                    scrollTop: $("#form-wizard-1").offset().top
                }, 900)
            },
            onPrevious: function (e, t, n) {
                var r = t.find("li").length;
                var i = n + 1;
                $(".step-title", $("#form-wizard-1")).text("Step " + (n + 1) + " of " + r);
                jQuery("li", $("#form-wizard-1")).removeClass("done");
                var s = t.find("li");
                for (var o = 0; o < n; o++) {
                    jQuery(s[o]).addClass("done")
                }
                if (i == 1) {
                    $("#form-wizard-1").find(".button-previous").hide()
                } else {
                    $("#form-wizard-1").find(".button-previous").show()
                } if (i >= r) {
                    $("#form-wizard-1").find(".button-next").hide();
                    $("#form-wizard-1").find(".button-submit").show()
                } else {
                    $("#form-wizard-1").find(".button-next").show();
                    $("#form-wizard-1").find(".button-submit").hide()
                }
                $("html, body").animate({
                    scrollTop: $("#form-wizard-1").offset().top
                }, 900)
            },
            onTabShow: function (e, t, n) {
                var r = t.find("li").length;
                var i = n + 1;
                var s = i / r * 100;
                $("#form-wizard-1").find(".bar").css({
                    width: s + "%"
                })
            }
        });
        $("#form-wizard-1").find(".button-previous").hide();
        $("#form-wizard-1 .button-submit").click(function () {
            alert("Finished!")
        }).hide();
        $("#form-wizard-2").bootstrapWizard({
            nextSelector: ".button-next",
            previousSelector: ".button-previous",
            onTabClick: function (e, t, n) {
                alert("on tab click disabled");
                return false
            },
            onNext: function (e, t, n) {
                var r = t.find("li").length;
                var i = n + 1;
                $(".step-title", $("#form-wizard-2")).text("Step " + (n + 1) + " of " + r);
                jQuery("li", $("#form-wizard-2")).removeClass("done");
                var s = t.find("li");
                for (var o = 0; o < n; o++) {
                    jQuery(s[o]).addClass("done")
                }
                if (i == 1) {
                    $("#form-wizard-2").find(".button-previous").hide()
                } else {
                    $("#form-wizard-2").find(".button-previous").show()
                } if (i >= r) {
                    $("#form-wizard-2").find(".button-next").hide();
                    $("#form-wizard-2").find(".button-submit").show()
                } else {
                    $("#form-wizard-2").find(".button-next").show();
                    $("#form-wizard-2").find(".button-submit").hide()
                }
                $("html, body").animate({
                    scrollTop: $("#form-wizard-2").offset().top
                }, 900)
            },
            onPrevious: function (e, t, n) {
                var r = t.find("li").length;
                var i = n + 1;
                $(".step-title", $("#form-wizard-2")).text("Step " + (n + 1) + " of " + r);
                jQuery("li", $("#form-wizard-2")).removeClass("done");
                var s = t.find("li");
                for (var o = 0; o < n; o++) {
                    jQuery(s[o]).addClass("done")
                }
                if (i == 1) {
                    $("#form-wizard-2").find(".button-previous").hide()
                } else {
                    $("#form-wizard-2").find(".button-previous").show()
                } if (i >= r) {
                    $("#form-wizard-2").find(".button-next").hide();
                    $("#form-wizard-2").find(".button-submit").show()
                } else {
                    $("#form-wizard-2").find(".button-next").show();
                    $("#form-wizard-2").find(".button-submit").hide()
                }
                $("html, body").animate({
                    scrollTop: $("#form-wizard-2").offset().top
                }, 900)
            },
            onTabShow: function (e, t, n) {
                var r = t.find("li").length;
                var i = n + 1;
                var s = i / r * 100;
                $("#form-wizard-2").find(".bar").css({
                    width: s + "%"
                })
            }
        });
        $("#form-wizard-2").find(".button-previous").hide();
        $("#form-wizard-2 .button-submit").click(function () {
            alert("Finished!")
        }).hide()
    }
    if (jQuery().validate) {
        var e = function (e) {
            $(e).closest(".control-group").removeClass("success")
        };
        $("#validation-form").validate({
            errorElement: "span",
            errorClass: "help-inline",
            focusInvalid: false,
            ignore: "",
            invalidHandler: function (e, t) {},
            highlight: function (e) {
                $(e).closest(".control-group").removeClass("success").addClass("error")
            },
            unhighlight: function (t) {
                $(t).closest(".control-group").removeClass("error");
                setTimeout(function () {
                    e(t)
                }, 3e3)
            },
            success: function (e) {
                e.closest(".control-group").removeClass("error").addClass("success")
            },
            submitHandler: function (e) {}
        })
    }
    if (jQuery().plot) {
        var t = [];
        var n = 250;

        function r() {
            if (t.length > 0) t = t.slice(1);
            while (t.length < n) {
                var e = t.length > 0 ? t[t.length - 1] : 50;
                var r = e + Math.random() * 10 - 5;
                if (r < 0) r = 0;
                if (r > 100) r = 100;
                t.push(r)
            }
            var i = [];
            for (var s = 0; s < t.length; ++s) i.push([s, t[s]]);
            return i
        }

        function i() {
            if ($("#chart_1").size() == 0) {
                return
            }
            var e = [];
            for (var t = 0; t < Math.PI * 2; t += .25) e.push([t, Math.sin(t)]);
            var n = [];
            for (var t = 0; t < Math.PI * 2; t += .25) n.push([t, Math.cos(t)]);
            var r = [];
            for (var t = 0; t < Math.PI * 2; t += .1) r.push([t, Math.tan(t)]);
            $.plot($("#chart_1"), [{
                label: "sin(x)",
                data: e
            }, {
                label: "cos(x)",
                data: n
            }, {
                label: "tan(x)",
                data: r
            }], {
                series: {
                    lines: {
                        show: true
                    },
                    points: {
                        show: true
                    }
                },
                xaxis: {
                    ticks: [0, [Math.PI / 2, "π/2"],
                        [Math.PI, "π"],
                        [Math.PI * 3 / 2, "3π/2"],
                        [Math.PI * 2, "2π"]
                    ]
                },
                yaxis: {
                    ticks: 10,
                    min: -2,
                    max: 2
                },
                grid: {
                    backgroundColor: {
                        colors: ["#fff", "#eee"]
                    }
                }
            })
        }

        function s() {
            function e() {
                return Math.floor(Math.random() * (1 + 40 - 20)) + 20
            }

            function i(e, t, n) {
                $('<div id="tooltip">' + n + "</div>").css({
                    position: "absolute",
                    display: "none",
                    top: t + 5,
                    left: e + 15,
                    border: "1px solid #333",
                    padding: "4px",
                    color: "#fff",
                    "border-radius": "3px",
                    "background-color": "#333",
                    opacity: .8
                }).appendTo("body").fadeIn(200)
            }
            if ($("#chart_2").size() == 0) {
                return
            }
            var t = [
                [1, e()],
                [2, e()],
                [3, 2 + e()],
                [4, 3 + e()],
                [5, 5 + e()],
                [6, 10 + e()],
                [7, 15 + e()],
                [8, 20 + e()],
                [9, 25 + e()],
                [10, 30 + e()],
                [11, 35 + e()],
                [12, 25 + e()],
                [13, 15 + e()],
                [14, 20 + e()],
                [15, 45 + e()],
                [16, 50 + e()],
                [17, 65 + e()],
                [18, 70 + e()],
                [19, 85 + e()],
                [20, 80 + e()],
                [21, 75 + e()],
                [22, 80 + e()],
                [23, 75 + e()],
                [24, 70 + e()],
                [25, 65 + e()],
                [26, 75 + e()],
                [27, 80 + e()],
                [28, 85 + e()],
                [29, 90 + e()],
                [30, 95 + e()]
            ];
            var n = [
                [1, e() - 5],
                [2, e() - 5],
                [3, e() - 5],
                [4, 6 + e()],
                [5, 5 + e()],
                [6, 20 + e()],
                [7, 25 + e()],
                [8, 36 + e()],
                [9, 26 + e()],
                [10, 38 + e()],
                [11, 39 + e()],
                [12, 50 + e()],
                [13, 51 + e()],
                [14, 12 + e()],
                [15, 13 + e()],
                [16, 14 + e()],
                [17, 15 + e()],
                [18, 15 + e()],
                [19, 16 + e()],
                [20, 17 + e()],
                [21, 18 + e()],
                [22, 19 + e()],
                [23, 20 + e()],
                [24, 21 + e()],
                [25, 14 + e()],
                [26, 24 + e()],
                [27, 25 + e()],
                [28, 26 + e()],
                [29, 27 + e()],
                [30, 31 + e()]
            ];
            var r = $.plot($("#chart_2"), [{
                data: t,
                label: "Unique Visits"
            }, {
                data: n,
                label: "Page Views"
            }], {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: .05
                            }, {
                                opacity: .01
                            }]
                        }
                    },
                    points: {
                        show: true
                    },
                    shadowSize: 2
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: "#eee",
                    borderWidth: 0
                },
                colors: ["#FCB322", "#A5D16C", "#52e136"],
                xaxis: {
                    ticks: 11,
                    tickDecimals: 0
                },
                yaxis: {
                    ticks: 11,
                    tickDecimals: 0
                }
            });
            var s = null;
            $("#chart_2").bind("plothover", function (e, t, n) {
                $("#x").text(t.x.toFixed(2));
                $("#y").text(t.y.toFixed(2));
                if (n) {
                    if (s != n.dataIndex) {
                        s = n.dataIndex;
                        $("#tooltip").remove();
                        var r = n.datapoint[0].toFixed(2),
                            o = n.datapoint[1].toFixed(2);
                        i(n.pageX, n.pageY, n.series.label + " of " + r + " = " + o)
                    }
                } else {
                    $("#tooltip").remove();
                    s = null
                }
            })
        }

        function o() {
            function o() {
                i = null;
                var e = s;
                var t = plot.getAxes();
                if (e.x < t.xaxis.min || e.x > t.xaxis.max || e.y < t.yaxis.min || e.y > t.yaxis.max) return;
                var n, o, u = plot.getData();
                for (n = 0; n < u.length; ++n) {
                    var a = u[n];
                    for (o = 0; o < a.data.length; ++o)
                        if (a.data[o][0] > e.x) break;
                    var f, l = a.data[o - 1],
                        c = a.data[o];
                    if (l == null) f = c[1];
                    else if (c == null) f = l[1];
                    else f = l[1] + (c[1] - l[1]) * (e.x - l[0]) / (c[0] - l[0]);
                    r.eq(n).text(a.label.replace(/=.*/, "= " + f.toFixed(2)))
                }
            }
            if ($("#chart_3").size() == 0) {
                return
            }
            var e = [],
                t = [];
            for (var n = 0; n < 14; n += .1) {
                e.push([n, Math.sin(n)]);
                t.push([n, Math.cos(n)])
            }
            plot = $.plot($("#chart_3"), [{
                data: e,
                label: "sin(x) = -0.00"
            }, {
                data: t,
                label: "cos(x) = -0.00"
            }], {
                series: {
                    lines: {
                        show: true
                    }
                },
                crosshair: {
                    mode: "x"
                },
                grid: {
                    hoverable: true,
                    autoHighlight: false
                },
                colors: ["#FCB322", "#A5D16C", "#52e136"],
                yaxis: {
                    min: -1.2,
                    max: 1.2
                }
            });
            var r = $("#chart_3 .legendLabel");
            r.each(function () {
                $(this).css("width", $(this).width())
            });
            var i = null;
            var s = null;
            $("#chart_3").bind("plothover", function (e, t, n) {
                s = t;
                if (!i) i = setTimeout(o, 50)
            })
        }

        function u() {
            function i() {
                n.setData([r()]);
                n.draw();
                setTimeout(i, t)
            }
            if ($("#chart_4").size() == 0) {
                return
            }
            var e = {
                series: {
                    shadowSize: 1
                },
                lines: {
                    show: true,
                    lineWidth: .5,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: .1
                        }, {
                            opacity: 1
                        }]
                    }
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    tickFormatter: function (e) {
                        return e + "%"
                    }
                },
                xaxis: {
                    show: false
                },
                colors: ["#6ef146"],
                grid: {
                    tickColor: "#a8a3a3",
                    borderWidth: 0
                }
            };
            var t = 30;
            var n = $.plot($("#chart_4"), [r()], e);
            i()
        }

        function a() {
            function a() {
                $.plot($("#chart_5"), [e, n, r], {
                    series: {
                        stack: i,
                        lines: {
                            show: o,
                            fill: true,
                            steps: u
                        },
                        bars: {
                            show: s,
                            barWidth: .6
                        }
                    }
                })
            }
            if ($("#chart_5").size() == 0) {
                return
            }
            var e = [];
            for (var t = 0; t <= 10; t += 1) e.push([t, parseInt(Math.random() * 30)]);
            var n = [];
            for (var t = 0; t <= 10; t += 1) n.push([t, parseInt(Math.random() * 30)]);
            var r = [];
            for (var t = 0; t <= 10; t += 1) r.push([t, parseInt(Math.random() * 30)]);
            var i = 0,
                s = true,
                o = false,
                u = false;
            $(".stackControls input").click(function (e) {
                e.preventDefault();
                i = $(this).val() == "With stacking" ? true : null;
                a()
            });
            $(".graphControls input").click(function (e) {
                e.preventDefault();
                s = $(this).val().indexOf("Bars") != -1;
                o = $(this).val().indexOf("Lines") != -1;
                u = $(this).val().indexOf("steps") != -1;
                a()
            });
            a()
        }

        function f() {
            function r(e, t, n) {
                if (!n) return;
                percent = parseFloat(n.series.percent).toFixed(2);
                $("#hover").html('<span style="font-weight: bold; color: ' + n.series.color + '">' + n.series.label + " (" + percent + "%)</span>")
            }

            function i(e, t, n) {
                if (!n) return;
                percent = parseFloat(n.series.percent).toFixed(2);
                alert("" + n.series.label + ": " + percent + "%")
            }
            if ($("#graph_1").size() == 0) {
                return
            }
            var e = [];
            var t = Math.floor(Math.random() * 10) + 1;
            for (var n = 0; n < t; n++) {
                e[n] = {
                    label: "Series" + (n + 1),
                    data: Math.floor((Math.random() - 1) * 100) + 1
                }
            }
            $.plot($("#graph_1"), e, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 1,
                            formatter: function (e, t) {
                                return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + e + "<br/>" + Math.round(t.percent) + "%</div>"
                            },
                            background: {
                                opacity: .8
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
            $.plot($("#graph_2"), e, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 3 / 4,
                            formatter: function (e, t) {
                                return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + e + "<br/>" + Math.round(t.percent) + "%</div>"
                            },
                            background: {
                                opacity: .5
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
            $.plot($("#graph_3"), e, {
                series: {
                    pie: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: true
                }
            });
            $("#graph_3").bind("plothover", r);
            $("#graph_3").bind("plotclick", i);
            $.plot($("#graph_4"), e, {
                series: {
                    pie: {
                        innerRadius: .5,
                        show: true
                    }
                }
            })
        }
        i();
        s();
        o();
        u();
        a();
        f()
    }
    if (jQuery().fullCalendar) {
        var l = new Date;
        var c = l.getDate();
        var h = l.getMonth();
        var p = l.getFullYear();
        var d = {};
        if ($(window).width() <= 320) {
            d = {
                left: "title, prev,next",
                center: "",
                right: "today,month,agendaWeek,agendaDay"
            }
        } else {
            d = {
                left: "title",
                center: "",
                right: "prev,next,today,month,agendaWeek,agendaDay"
            }
        }
        var v = function (e) {
            var t = {
                title: $.trim(e.text())
            };
            e.data("eventObject", t);
            e.draggable({
                zIndex: 999,
                revert: true,
                revertDuration: 0
            })
        };
        var m = function (e, t) {
            e = e.length == 0 ? "Untitled Event" : e;
            t = t.length == 0 ? "default" : t;
            var n = $('<div data-class="label label-' + t + '" class="external-event label label-' + t + '">' + e + "</div>");
            jQuery("#event_box").append(n);
            v(n)
        };
        $("#external-events div.external-event").each(function () {
            v($(this))
        });
        $("#event_add").click(function () {
            var e = $("#event_title").val();
            var t = $("#event_priority").val();
            m(e, t)
        });
        var g = function () {
            $("#event_priority_chzn .chzn-search").hide();
            $("#event_priority_chzn_o_1").html('<span class="label label-default">' + $("#event_priority_chzn_o_1").text() + "</span>");
            $("#event_priority_chzn_o_2").html('<span class="label label-success">' + $("#event_priority_chzn_o_2").text() + "</span>");
            $("#event_priority_chzn_o_3").html('<span class="label label-info">' + $("#event_priority_chzn_o_3").text() + "</span>");
            $("#event_priority_chzn_o_4").html('<span class="label label-warning">' + $("#event_priority_chzn_o_4").text() + "</span>");
            $("#event_priority_chzn_o_5").html('<span class="label label-important">' + $("#event_priority_chzn_o_5").text() + "</span>")
        };
        $("#event_priority_chzn").click(g);
        m("My Event 1", "default");
        m("My Event 2", "success");
        m("My Event 3", "info");
        m("My Event 4", "warning");
        m("My Event 5", "important");
        m("My Event 6", "success");
        m("My Event 7", "info");
        m("My Event 8", "warning");
        m("My Event 9", "success");
        m("My Event 10", "default");
        $("#calendar").fullCalendar({
            header: d,
            editable: true,
            droppable: true,
            drop: function (e, t) {
                var n = $(this).data("eventObject");
                var r = $.extend({}, n);
                r.start = e;
                r.allDay = t;
                r.className = $(this).attr("data-class");
                $("#calendar").fullCalendar("renderEvent", r, true);
                if ($("#drop-remove").is(":checked")) {
                    $(this).remove()
                }
            },
            events: [{
                title: "All Day Event",
                start: new Date(p, h, 1),
                className: "label label-default"
            }, {
                title: "Long Event",
                start: new Date(p, h, c - 5),
                end: new Date(p, h, c - 2),
                className: "label label-success"
            }, {
                id: 999,
                title: "Repeating Event",
                start: new Date(p, h, c - 3, 16, 0),
                allDay: false,
                className: "label label-default"
            }, {
                id: 999,
                title: "Repeating Event",
                start: new Date(p, h, c + 4, 16, 0),
                allDay: false,
                className: "label label-important"
            }, {
                title: "Meeting",
                start: new Date(p, h, c, 10, 30),
                allDay: false,
                className: "label label-info"
            }, {
                title: "Lunch",
                start: new Date(p, h, c, 12, 0),
                end: new Date(p, h, c, 14, 0),
                allDay: false,
                className: "label label-warning"
            }, {
                title: "Birthday Party",
                start: new Date(p, h, c + 1, 19, 0),
                end: new Date(p, h, c + 1, 22, 30),
                allDay: false,
                className: "label label-success"
            }, {
                title: "Click for Google",
                start: new Date(p, h, 28),
                end: new Date(p, h, 29),
                url: "http://google.com/",
                className: "label label-warning"
            }]
        });
        $(".fc-button").addClass("btn")
    }
    if (jQuery().prettyPhoto) {
        $(".gallery a[rel^='prettyPhoto']").prettyPhoto({
            social_tools: "",
            hideflash: true
        })
    }
    if (jQuery.plot) {
        var y = $("#visitors-chart");
        if ($(y).size() == 0) {
            return
        }
        var b = [
            [1, 35],
            [2, 48],
            [3, 34],
            [4, 54],
            [5, 46],
            [6, 37],
            [7, 40],
            [8, 55],
            [9, 43],
            [10, 61],
            [11, 52],
            [12, 57],
            [13, 64],
            [14, 56],
            [15, 48],
            [16, 53],
            [17, 50],
            [18, 59],
            [19, 66],
            [20, 73],
            [21, 81],
            [22, 75],
            [23, 86],
            [24, 77],
            [25, 86],
            [26, 85],
            [27, 79],
            [28, 83],
            [29, 95],
            [30, 92]
        ];
        var w = [
            [1, 9],
            [2, 15],
            [3, 16],
            [4, 21],
            [5, 19],
            [6, 15],
            [7, 22],
            [8, 29],
            [9, 20],
            [10, 27],
            [11, 32],
            [12, 37],
            [13, 34],
            [14, 30],
            [15, 28],
            [16, 23],
            [17, 28],
            [18, 35],
            [19, 31],
            [20, 28],
            [21, 33],
            [22, 25],
            [23, 27],
            [24, 24],
            [25, 36],
            [26, 25],
            [27, 39],
            [28, 28],
            [29, 35],
            [30, 42]
        ];
        var E = ["#88bbc8", "#ed7a53", "#9FC569", "#bbdce3", "#9a3b1b", "#5a8022", "#2c7282"];
        var S = {
            grid: {
                show: true,
                aboveData: true,
                color: "#3f3f3f",
                labelMargin: 5,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 5,
                clickable: true,
                hoverable: true,
                autoHighlight: true,
                mouseActiveRadius: 20
            },
            series: {
                grow: {
                    active: false,
                    stepMode: "linear",
                    steps: 50,
                    stepDelay: true
                },
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 3,
                    steps: false
                },
                points: {
                    show: true,
                    radius: 4,
                    symbol: "circle",
                    fill: true,
                    borderColor: "#fff"
                }
            },
            legend: {
                position: "ne",
                margin: [0, -25],
                noColumns: 0,
                labelBoxBorderColor: null,
                labelFormatter: function (e, t) {
                    return e + "  "
                }
            },
            yaxis: {
                min: 0
            },
            xaxis: {
                ticks: 11,
                tickDecimals: 0
            },
            colors: E,
            shadowSize: 1,
            tooltip: true,
            tooltipOpts: {
                content: "%s : %y.0",
                defaultTheme: false,
                shifts: {
                    x: -30,
                    y: -50
                }
            }
        };
        $.plot(y, [{
            label: "Visits",
            data: b,
            lines: {
                fillColor: "#f2f7f9"
            },
            points: {
                fillColor: "#88bbc8"
            }
        }, {
            label: "Unique Visits",
            data: w,
            lines: {
                fillColor: "#fff8f2"
            },
            points: {
                fillColor: "#ed7a53"
            }
        }], S)
    }
    if (jQuery().sparkline) {
        $(".inline-sparkline").sparkline("html", {
            width: "70px",
            height: "26px",
            lineWidth: 2,
            spotRadius: 3,
            lineColor: "#88bbc8",
            fillColor: "#f2f7f9",
            spotColor: "#14ae48",
            maxSpotColor: "#e72828",
            minSpotColor: "#f7941d"
        })
    }
})