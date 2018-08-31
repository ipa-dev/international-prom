jQuery(function($){
    $('.matchheight').matchHeight();
});
jQuery(document).ready(function() {
    jQuery(".fancybox").fancybox({

    });
    jQuery('#datetimepicker').datetimepicker({
        timepicker:false,
        format:'m/d/Y'
    });
});
jQuery(document).ready(function() {
    jQuery('.external-events li a').each(function () {
        jQuery(this).data('event', {
            title: jQuery(this).data('type'),
            stick: true
        });

        jQuery(this).draggable({
            zIndex: 999,
            revert: true,
            revertDuration: 0
        });

    });
});
/*var formapp = angular.module("socialForm", []);
formapp.controller("socialCtrl", function($scope, $http) {

});*/
var app = angular.module('myApp', ['ui.calendar']);
app.controller('myNgController', ['$scope', '$compile', '$http', 'uiCalendarConfig', function ($scope, $compile, $http, uiCalendarConfig) {

    var site_url = jQuery('input[name="site_url"]').val();

    $scope.SelectedEvent = null;
    var isFirstTime = true;

    $scope.events = [];
    $scope.eventSources = [$scope.events];

    $scope.formData = [];

    jQuery('#Loader').show();
    $http({
        cache: false,
        url: MyAjax.ajaxurl,
        method: "POST",
        params: {action: "mcal_action"}
    }).then(function (data) {
        $scope.events.slice(0, $scope.events.length);
        angular.forEach(data.data, function (value) {
            var editable;
            if(value.editable == 'false') {
                editable = false;
            } else {
                editable = true;
            }
            $scope.events.push({
                title: value.Title,
                description: value.Description,
                start: value.StartAt,
                //end: value.EndAt,
                allDay: false,
                displayEventTime: true,
                stick: true,
                editable: editable,
                className: 'fc-social fc-'+value.Icon+' '+value.ExtraClass,
                id: value.ID,
                globalShare: value.globalShare,
                globalSuggestions: value.globalSuggestions,
                globalAnnouncements: value.globalAnnouncements
            });
        });
        jQuery('#Loader').hide();
    });

    $scope.processFormDelete = function() {
        jQuery.confirm({
            title: 'Confirm Deletion!',
            content: 'Warning! You are about to delete an entire post. This Can not be undone. Are you sure you wish to delete this?',
            buttons: {
                delete: {
                    text: 'Delete',
                    btnClass: 'btn-blue',
                    animation: 'zoom',
                    closeAnimation: 'scale',
                    keys: ['enter'],
                    action: function(){
                        jQuery('#Loader').show();
                        $http({
                            method  : 'POST',
                            url     : MyAjax.ajaxurl,
                            params: {action: "edit_data", eventId: $scope.formData.eventId, event_name: $scope.formData.event_name, event_date: $scope.formData.event_date, event_content: $scope.formData.event_content, submitValue: 'delete'}
                        }).then(function (data) {
                            //jQuery('#Loader').hide();
                            var value = data.data;
                            jQuery.fancybox( '<p class="successMsg">Successfully updated.</p>' );
                            jQuery.fancybox.close();
                            location.reload();
                        });
                    }
                },
                cancel: function () {
                    //jQuery.alert('Canceled!');
                }
            }
        });
    }

    $scope.processFormEdit = function() {
        $scope.formData.Pinboard = jQuery('input[name="Pinboard"]').val();
        jQuery('#Loader').show();
        $scope.formData.event_date = jQuery('#datepicker_hidden').val();
        $scope.formData.event_time = jQuery('#timepicker_hidden').val();
        $scope.formData.ImageSelector = jQuery('#ImageSelector').val();
        $http({
            method  : 'POST',
            url     : MyAjax.ajaxurl,
            params: {action: "edit_data", eventId: $scope.formData.eventId, event_name: $scope.formData.event_name, event_date: $scope.formData.event_date, event_time: $scope.formData.event_time, event_content: $scope.formData.event_content, event_image: $scope.formData.ImageSelector, subjectLine: $scope.formData.subjectLine, previewtext: $scope.formData.previewtext, fromName: $scope.formData.fromName, fromEmail: $scope.formData.fromEmail, Pinboard: $scope.formData.Pinboard}
        }).then(function (data) {
            //jQuery('#Loader').hide();
            var value = data.data;
            jQuery.fancybox( '<p class="successMsg">Successfully updated.</p>' );
            jQuery.fancybox.close();
            location.reload();
        });
    };

    $scope.processForm = function() {
        $scope.formData.Pinboard = jQuery('input[name="Pinboard"]').val();
        if($scope.formData.type == 'pinterest' && ($scope.formData.Pinboard == 'NULL' || $scope.formData.Pinboard == '')) {
            alert('Select a Board');
        } else {
            jQuery('#Loader').show();
            $scope.formData.event_date = jQuery('#datepicker_hidden').val();
            $scope.formData.event_time = jQuery('#timepicker_hidden').val();
            $scope.formData.ImageSelector = jQuery('#ImageSelector').val();
            if ($scope.formData.type == 'email') {
                $scope.formData.event_content = jQuery('.emailEditor').val();
            }
            $http({
                method: 'POST',
                url: MyAjax.ajaxurl,
                params: {
                    action: "save_data",
                    type: $scope.formData.type,
                    event_name: $scope.formData.event_name,
                    event_date: $scope.formData.event_date,
                    event_time: $scope.formData.event_time,
                    event_content: $scope.formData.event_content,
                    event_image: $scope.formData.ImageSelector,
                    subjectLine: $scope.formData.subjectLine,
                    previewtext: $scope.formData.previewtext,
                    fromName: $scope.formData.fromName,
                    fromEmail: $scope.formData.fromEmail,
                    Pinboard: $scope.formData.Pinboard
                }
            }).then(function (data) {
                //jQuery('#Loader').hide();
                var value = data.data;
                console.log(value);
                jQuery.fancybox('<p class="successMsg">Successfully updated.</p>');
                jQuery.fancybox.close();
                location.reload();
                /*$scope.events.push({
                    title: value.Title,
                    description: value.Description,
                    start: value.StartAt,
                    end: value.EndAt,
                    allDay: true,
                    stick: true,
                    className: 'fc-social fc-'+value.Icon
                });*/
            });
        }
    };

    /*$scope.onFileSelect = function ($files) {
        $scope.selectedFile = $files;
    };*/

    //Load events from server
    $scope.renderCalenderData = function(datatype) {
        if(datatype === 'undefined') {
            datatype = 0;
        }
        $scope.events = [];
        $http({
            cache: false,
            url: MyAjax.ajaxurl,
            method: "POST",
            params: {action: "mcal_action", datatype: datatype}
        }).then(function (data) {
            $scope.events.slice(0, $scope.events.length);
            angular.forEach(data.data, function (value) {
                $scope.events.push({
                    title: value.Title,
                    description: value.Description,
                    start: value.StartAt,
                    //end: value.EndAt,
                    allDay: false,
                    displayEventTime: true,
                    stick: true,
                    className: 'fc-social fc-'+value.Icon+' '+value.ExtraClass,
                    id: value.ID,
                    globalShare: value.globalShare,
                    globalSuggestions: value.globalSuggestions,
                    globalAnnouncements: value.globalAnnouncements
                });
            });
        });
        $scope.eventSources = [$scope.events];
        $scope.renderCalender();
    };

    var date_now = jQuery('input[name="date_now"]').val();

    //configure calendar
    $scope.uiConfig = {
        calendar: {
            height: 450,
            editable: true,
            droppable: true,
            displayEventTime: false,
            customButtons: {
                myCustomButton: {
                    text: 'Add',
                    click: function() {
                        alert('clicked the custom button!');
                    }
                }
            },
            defaultDate: date_now,
            header: {
                left: 'prev,next today',
                center: 'title',
                right:''
            },
            /*defaultView: 'agendaWeek',*/
            /*eventClick: function (event) {
                $scope.SelectedEvent = event;
            },*/
            contentHeight: 800,
            eventReceive: function(event) {
                var title = event.title;
                var start = event.start.format("YYYY-MM-DD");
                jQuery('#calendar').fullCalendar('removeEvents',event._id);
                jQuery('#Loader').show();
                var width, height;
                if(title == 'email') {
                    width = 1040;
                    height = 540;
                    //window.location = site_url+'/email-editor/?start_date='+start;
                } else {
                    width = 1040;
                    height = 540;
                }
                $http({
                    cache: false,
                    url: MyAjax.ajaxurl,
                    method: "POST",
                    params: {action: "dropPopup", type: title, start: start}
                }).then(function (data) {
                    jQuery.fancybox.open({
                        width: width,
                        height: height,
                        fitToView: false,
                        autoSize: false,
                        content: $compile(data.data)($scope),
                        /*href: MyAjax.ajaxurl,
                        type: "ajax",
                        ajax: {
                            type: "POST",
                            data: {
                                action: "dropPopup", type: title, start: start,
                            }
                        },*/
                        afterLoad: function () {
                            jQuery('#datetimepicker').datetimepicker({
                                timepicker: false,
                                format: 'm/d/Y'
                            });
                        }
                    });
                    jQuery('#Loader').hide();
                    jQuery('.talk-bubble').hide();
                });
                /*var site_url = jQuery('input[name="site_url"]').val();
                location.href = site_url+'/email-editor/?start_date='+start;*/
                //$scope.renderCalender();
            },
            eventDrop: function(event, delta, revertFunc) {
                //alert(event.id + " was dropped on " + event.start.format());
                $http({
                    cache: false,
                    url: MyAjax.ajaxurl,
                    method: "POST",
                    params: {action: "dropPopupEditDate", EditID: event.id, event_date: event.start.format()}
                }).then(function (data) {

                });
            },
            dayClick: function(date, jsEvent, view) {
                /*alert('Clicked on: ' + date.format());
                alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                alert('Current view: ' + view.name);
                jQuery(this).css('background-color', 'red');*/
                //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                var top = jsEvent.pageY - 213;
                var left = jsEvent.pageX + 20;
                var start = date.format("YYYY-MM-DD");
                jQuery('.talk-bubble').css('top', top);
                jQuery('.talk-bubble').css('left', left);
                jQuery('.talk-bubble').show();
                jQuery('.addCalenderDataStart').val(start);
            },
            eventClick: function(event, jsEvent, view) {
                jQuery('.talk-bubble').hide();
                if(event.globalAnnouncements != 1 && event.globalSuggestions != 1 && event.globalShare != 1) {
                    jQuery('#Loader').show();
                    $http({
                        cache: false,
                        url: MyAjax.ajaxurl,
                        method: "POST",
                        params: {action: "getEventType", EventID: event.id}
                    }).then(function (data) {
                        if (data.data == 'email') {
                            width = 1040;
                            height = 540;
                            //window.location = site_url;
                        } else {
                            width = 1040;
                            height = 540;
                        }
                        $http({
                            cache: false,
                            url: MyAjax.ajaxurl,
                            method: "POST",
                            params: {action: "dropPopupEdit", EditID: event.id}
                        }).then(function (data) {
                            jQuery.fancybox.open({
                                width: width,
                                height: height,
                                fitToView: false,
                                autoSize: false,
                                content: $compile(data.data)($scope),
                                afterLoad: function () {
                                    /*jQuery('#datepicker').datetimepicker({
                                        timepicker:false,
                                        format:'Y-m-d'
                                    });*/
                                }
                            });
                            jQuery('#Loader').hide();
                        });
                    });
                } else {
                    jQuery('#Loader').show();
                    $http({
                        cache: false,
                        url: MyAjax.ajaxurl,
                        method: "POST",
                        params: {action: "globalEvents", EditID: event.id}
                    }).then(function (data) {
                        var content = '<div style="clear: both;"><div style="float: left; width: 50%; text-align: left;"><strong>Date: </strong>'+data.data.date+'</div><div style="float: left; width: 50%; text-align: right;"><strong>Time: </strong>'+data.data.time+'</div></div><br/><br/><br/><div class="jconfirm-title-c jconfirm-hand"><span class="jconfirm-icon-c"></span><span class="jconfirm-title">'+data.data.title+'</span></div><div>'+data.data.content+'</div>';
                        jQuery('#Loader').hide();
                        if(event.globalShare == 1) {
                            jQuery.alert({
                                title: '',
                                content: content,
                                type: 'green',
                            });
                        }
                        if(event.globalSuggestions == 1) {
                            jQuery.alert({
                                title: '',
                                content: content,
                                type: 'red',
                                buttons: {
                                    optOut: {
                                        text: 'Opt Out',
                                        btnClass: 'btn-red',
                                        action: function(){
                                            $http({
                                                cache: false,
                                                url: MyAjax.ajaxurl,
                                                method: "POST",
                                                params: {action: "optOut", EditID: event.id}
                                            }).then(function (data) {
                                                if(data.data == 1) {
                                                    jQuery.alert('You are opted out');
                                                    location.reload();
                                                }
                                            });
                                        }
                                    },
                                    close: function () {
                                    }
                                }
                            });

                        }
                        if(event.globalAnnouncements == 1) {
                            jQuery.alert({
                                title: '',
                                content: content,
                                type: 'dark',
                            });
                        }
                    });
                }
            },
            eventAfterAllRender: function () {
                if ($scope.events.length > 0 && isFirstTime) {
                    //Focus first event
                    //uiCalendarConfig.calendars.myCalendar.fullCalendar('gotoDate', $scope.events[0].start);
                    //isFirstTime = false;
                }
            },
            /*eventRender: function(event, element) {
                if(event.icon){
                    console.log(event.icon);
                    element.find(".fc-title").prepend("<i class='fa fa-"+event.icon+"'></i>");
                }
            }*/
        }
    };

    $scope.addCalenderData = function(type) {
        jQuery('#Loader').show();
        if(type == 'email') {
            width = 1040;
            height = 540;
        } else {
            width = 1040;
            height = 540;
        }
        var start = jQuery('.addCalenderDataStart').val();
        $http({
            cache: false,
            url: MyAjax.ajaxurl,
            method: "POST",
            params: {action: "dropPopup", type: type, start: start}
        }).then(function (data) {
            jQuery.fancybox.open({
                width: width,
                height: height,
                fitToView: false,
                autoSize: false,
                content: $compile(data.data)($scope)
            });
            jQuery('#Loader').hide();
        });
        jQuery('.talk-bubble').hide();
    };

    $scope.renderCalender = function() {
        if(uiCalendarConfig.calendars[calendar]){
            uiCalendarConfig.calendars.myCalendar.fullCalendar('render');
        }
    };

    $scope.changeView = function(view) {
        uiCalendarConfig.calendars.myCalendar.fullCalendar('changeView',view);
        $scope.renderCalender();
    };

    $scope.fancyboxImageSelector = function() {
        jQuery('.socialMediaPostContent').hide();
        jQuery('#Loader').show();
        $http({
            cache: false,
            url: MyAjax.ajaxurl,
            method: "POST",
            params: {action: "fancyboxImageSelector", EditID: event.id}
        }).then(function (data) {
            jQuery('.ImageSelectWrap').html(data.data);
            jQuery('.ImageSelectWrap').show();
            jQuery('#Loader').hide();
        });
    }

}])


function ImageSelector(Img) {
    jQuery('#Loader').show();
    jQuery('.socialMediaPostContent').show();
    jQuery('.ImageSelectWrap').hide();
    jQuery('input[name="ImageSelector"]').val(Img);
    jQuery('.attachedImage').attr('src', Img);
    jQuery('.attachedImageButton').text('Change Image');
    jQuery('input[name="ImageSelector"]').attr('ng-init', "formData.ImageSelector='"+Img+"'");
    jQuery('#Loader').hide();
    tinymce.editors[0].insertContent('<img src="'+Img+'">');
}
jQuery(document).keyup(function(e) {
    if (e.keyCode == 27) {
        jQuery('.talk-bubble').hide();
    }
});