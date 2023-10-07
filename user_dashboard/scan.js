
    function scanNavlink() {
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_nav_link',
                fullname: fullname
            },
            success: function(result) {
                let html = '';
                var loop_count = 0;
                var icon_adjust = 0;
                $.each(result, function(key, value) {
                    loop_count++;
                    let icon_preview = '';
                    icon_adjust += 4;
                    icon_preview = `<span class="position-absolute top-${icon_adjust} start-100 translate-middle badge rounded-pill bg-danger total-notif-${value.app_id}"></span>`;
                    
                    let uniq = $('.system_name_' + loop_count).val();
                    if (uniq != value.app_name) {
                        html += `<li><a class="notification-nav-link" onclick="loadRequestEmail('${value.app_name}')"><input type="hidden" class="system_name_${loop_count}" id="system_name_${loop_count}" value="${value.app_name}">${value.app_name}${icon_preview}</a></li>`;
                        setTimeout(function() {
                            loadNavLinkTableData();
                        }, 500);
                        $('.loadNavLink').append(html);
                        
                        $('.notification-nav-link').click(function(e) {
                            e.preventDefault();
                            $('.notification-nav-link').removeClass('active');
                            $(this).addClass('active').fadeIn();
                        });
                        $('.notification-nav-link:first').addClass('active');
                    }
                });
            }
        });
    }
    
    function scanRequest(category) {
        switch (category){
            case 'IT Repair and Request':
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'email_notification',
                        fullname: fullname,
                        category: category
                    },
                    success: function(result) {
                        var requestNotifCount = 0;
                        var repairNotifCount = 0;
                        let html = '';
                        var scan = 0;
                        var scanDetails = 0;
                        $.each(result, function(date, row) {
                            scan++;
                            $.each(row, function(key, details) {
                                scanDetails++;
                            });
                        });
                        $.each(result, function(date, row) {
                            let displayDate = formatDate(date);
                            scan--;
                            let uniq = $('.date_uniq' + scan).val();
                            let notExist = false;
                            if (uniq != date) {
                                html += '<tr>';
                                html += '<th colspan="7" class="position-relative" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_uniq' + scan + '" id="date_uniq' + scan + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                                html += '</tr>';
                                notExist = true;
                            }
                            $.each(row, function(key, details) {
                            $('.nav-item:eq(0)').css('display', 'block');
                            $('.request-status').addClass('ishowmo');
                                scanDetails--;
                                let table_name = details.table_name == 'tblit_repair' ? 'Repair Request Main' : 'Software and Hardware Request Main';
                                if (key === 0) {
                                    html += '<tr class="text" id=itr' + displayDate + '">';
                                    html += '</tr>';
                                }
                                let uniqFrom = $('.from_uniq' + scanDetails).val();
                                if (uniqFrom != details.notificationid) {
                                    // loadReceivedEmail(details.app_name);
                                    if (notExist) {
                                        html += '<tr>';
                                    }
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="messageStatus' + scanDetails + '" id="messageStatus' + scanDetails + '" value="' + details.cancel_status + '"><input type="hidden" class="messageBehaviorRepairStatus' + scanDetails + '" id="messageBehaviorRepairStatus' + scanDetails + '" value="' + details.repair_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + scanDetails + '" id="messageBehaviorRequestStatus' + scanDetails + '" value="' + details.prepared_by_acknowledge + '"><input type="hidden" class="from_uniq' + scanDetails + '" id="from_uniq' + scanDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + ' </td>';
                                    
                                    // ? ===== Status Action ===== ? //

                                    switch (details.table_name) {
                                        case 'tblit_request':
                                            // html += '<td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                                            html += '<td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                                            if (details.approved_by_acknowledge == false) {
                                                $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                            } else {
                                                if (details.noted_by_acknowledge == false) {
                                                    $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                                } else {
                                                    if (details.repair_by_acknowledge == false) {
                                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                                    } else {
                                                        if (details.prepared_by_acknowledge == false) {
                                                            requestNotifCount++;
                                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                                        } else {
                                                            $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                                        }
                                                    }
                                                }
                                            }
                                            break;
                                        case 'tblit_repair':
                                            html += '<td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                                            if (details.prepared_by_acknowledge == true) {
                                                $button_color = 'btn-warning';
                                                $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                            } else {
                                                repairNotifCount++;
                                                $button_color = 'btn-dark';
                                                $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                            }
                                            break;
                                    }
                                    // ? ===== Button Action ===== ? //

                                    switch (details.table_name) {
                                        case 'tblit_request':
                                            if (details.repair_by_acknowledge == true) {
                                                if (details.prepared_by_acknowledge == true) {
                                                    $button_color = 'btn-warning';
                                                    $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                                } else {
                                                    $button_color = 'btn-dark';
                                                    $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                                }
                                                html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' repair-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                            } else {
                                                html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn btn-info repair-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                            }
                                            break;
                                        case 'tblit_repair':
                                            html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + '  repair-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                            break;
                                    }
                                    if (notExist) {
                                        html += '</tr>';
                                        $(".data").prepend(html);
                                    } else {
                                        var classElement = document.createElement("tr");
                                        classElement.innerHTML = html;
                                        var trId = document.getElementById('itr'+displayDate);
                                        trId.parentNode.insertBefore(classElement, trId.nextSibling);
                                    }

                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                    $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                                    if(repairNotifCount > 0 || requestNotifCount > 0){
                                        let totalReqRepairCount = parseInt($('#req-count').val(), 10) + parseInt($('#req-repair-count').val(), 10);
                                        let totalCount = totalReqRepairCount + 1;
                                        totalCount == 0 ? $('.req-count').html('') : $('.req-count').html(totalCount + '+');
                                        let totalVal = parseInt($('#total-count').val(), 10) + 1;
                                        $('#total-count').val(totalVal)
                                        totalVal == 0 ? $('.total-notif-8').html('') : $('.total-notif-8').html(totalVal + '+');

                                        if(requestNotifCount > 0){
                                            $('#req-count').val(parseInt($('#req-count').val(), 10) + 1);
                                        }else if(repairNotifCount > 0){
                                            $('#req-repair-count').val(parseInt($('#req-repair-count').val(), 10) + 1);
                                        }
                                    }
                                }
                            });
                        });
                    }
                });
                break;
                case 'Physical Security': 
                    $.ajax({
                        url: '../controller/notification_controller/notification_module_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'email_request',
                            fullname: fullname,
                            category: category
                        },
                        success: function(result) {
                            var requestNotifCount = 0;
                            var repairNotifCount = 0;
                            let html = '';
                            var count = 0;
                            var scanDetails = 0;
                            $.each(result, function(date, row) {
                                count++;
                                $.each(row, function(key, details) {
                                    scanDetails++;
                                });
                            });
                            $.each(result, function(date, row) {
                                $('.nav-item:eq(0)').css('display', 'block');
                                $('.request-status').addClass('ishowmo');
                                count--;
                                let displayDate = formatDate(date);
                                let uniq = $('.date_uniq' + count).val();
                                let notExist = false;
                                if (uniq != date) {
                                    html += '<tr>';
                                    html += '<th colspan="7" class="position-relative" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_uniq' + count + '" id="date_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                                    html += '</tr>';
                                    notExist = true;
                                }
                                $.each(row, function(key, details) {
                                    scanDetails--;
                                    if (key === 0) {
                                        html += '<tr class="hide-tr" id="phd' + displayDate + '">';
                                        html += '</tr>';
                                    }
                                    let table_name = details.table_name == 'phd_time_sync_log_header' ? 'Time Synchronization Monitoring Log Sheet' : '';
                                    
                                    let uniqFrom = $('.from_uniq' + scanDetails).val();
                                    if (uniqFrom != details.notificationid) {
                                        if (notExist) {
                                            html += '<tr>';
                                        }
                                        html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorNotedStatus' + scanDetails + '" id="messageBehaviorNotedStatus' + scanDetails + '" value="' + details.noted_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + scanDetails + '" id="messageBehaviorRequestStatus' + scanDetails + '" value="' + details.prepared_by_acknowledge + '"><input type="hidden" class="from_uniq' + scanDetails + '" id="from_uniq' + scanDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                        html += '<td style="vertical-align: middle;">' + details.remarks + '</td>';
                                        if (details.checked_by_acknowledge == false) {
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        } else {
                                            if (details.noted_by_acknowledge == false) {
                                                $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                            } else {
                                                if (details.prepared_by_acknowledge == false) {
                                                    requestNotifCount++;
                                                    $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                                } else {
                                                    $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                                }
                                            }
                                        }
                                        switch (details.table_name) {
                                            case 'phd_time_sync_log_header':
                                                if (details.noted_by_acknowledge == true) {
                                                    if (details.prepared_by_acknowledge == true) {
                                                        $button_color = 'btn-warning';
                                                        $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                                    } else {
                                                        $button_color = 'btn-dark';
                                                        $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                                    }
                                                    html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' request-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                                } else {
                                                    html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn btn-info request-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                                }
                                                break;
                                        }
                                        if (notExist) {
                                            html += '</tr>';
                                            $(".data").prepend(html);
                                        } else {
                                            var classElement = document.createElement("tr");
                                            classElement.innerHTML = html;
                                            var trId = document.getElementById('phd'+displayDate);
                                            trId.parentNode.insertBefore(classElement, trId.nextSibling);
                                        }
                                        $('[data-bs-toggle="tooltip"]').tooltip();
                                        $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                                        if(requestNotifCount > 0){
                                            let reqInc = parseInt($('#req-count').val(), 10) + 1;
                                            $('#req-count').val(reqInc);
                                            reqInc == 0 ? $('.req-count').html('') : $('.req-count').html(reqInc + '+');

                                            let totalVal = parseInt($('#total-count-phd').val(), 10) + 1;
                                            $('#total-count-phd').val(totalVal)
                                            totalVal == 0 ? $('.total-notif-6').html('') : $('.total-notif-6').html(totalVal + '+');
                                        }
                                    }
                                });
                            });
                        }
                    });
                    break;
                case 'Info Security': 
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'email_request',
                        fullname: fullname,
                        category: category
                    },
                    success: function(result) {
                        var requestNotifCount = 0;
                        var repairNotifCount = 0;
                        let html = '';
                        var scan = 0;
                        var scanDetails = 0;
                        $.each(result, function(date, row) {
                            scan++;
                            $.each(row, function(key, details) {
                                scanDetails++;
                            });
                        });
                        $.each(result, function(date, row) {
                            let displayDate = formatDate(date);
                            scan--;
                            let uniq = $('.date_uniq' + scan).val();
                            let notExist = false;
                            if (uniq != date) {
                                html += '<tr>';
                                html += '<th colspan="7" class="position-relative" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_uniq' + scan + '" id="date_uniq' + scan + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                                html += '</tr>';
                                notExist = true;
                            }
                            $.each(row, function(key, details) {
                            $('.nav-item:eq(0)').css('display', 'block');
                            $('.request-status').addClass('ishowmo');
                                scanDetails--;
                                let table_name = details.table_name == 'info_sec_web_app_request' ? 'Web Application' : '';
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="infosec' + displayDate + '">';
                                    html += '</tr>';
                                }
                                let uniqFrom = $('.from_uniq' + scanDetails).val();
                                if (uniqFrom != details.notificationid) {
                                    // loadReceivedEmail(details.app_name);
                                    if (notExist) {
                                        html += '<tr>';
                                    }
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorRepairStatus' + scanDetails + '" id="messageBehaviorRepairStatus' + scanDetails + '" value="' + details.repair_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + scanDetails + '" id="messageBehaviorRequestStatus' + scanDetails + '" value="' + details.prepared_by_acknowledge + '"><input type="hidden" class="from_uniq' + scanDetails + '" id="from_uniq' + scanDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + ' </td>';
                                    
                                    // ? ===== Status Action ===== ? //
                                    // html += '<td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                                    html += '<td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                                    if (details.approved_by_acknowledge == false) {
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    } else {
                                        if (details.noted_by_acknowledge == false) {
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        } else {
                                            if (details.repair_by_acknowledge == false) {
                                                $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                            } else {
                                                if (details.prepared_by_acknowledge == false) {
                                                    requestNotifCount++;
                                                    $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                                } else {
                                                    $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                                }
                                            }
                                        }
                                    }
                                    // ? ===== Button Action ===== ? //

                                    if (details.repair_by_acknowledge == true) {
                                        if (details.prepared_by_acknowledge == true) {
                                            $button_color = 'btn-warning';
                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                        } else {
                                            $button_color = 'btn-dark';
                                            $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                        }
                                        html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' request-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                    } else {
                                        html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn btn-info request-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                    }
                                    if (notExist) {
                                        html += '</tr>';
                                        $(".data").prepend(html);
                                    } else {
                                        var classElement = document.createElement("tr");
                                        classElement.innerHTML = html;
                                        var trId = document.getElementById('infosec'+displayDate);
                                        trId.parentNode.insertBefore(classElement, trId.nextSibling);
                                    }

                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                    $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                                    if(requestNotifCount > 0){
                                        let reqInc = parseInt($('#req-count').val(), 10) + 1;
                                        $('#req-count').val(reqInc);
                                        reqInc == 0 ? $('.req-count').html('') : $('.req-count').html(reqInc + '+');

                                        let totalVal = parseInt($('#total-count-info').val(), 10) + 1;
                                        $('#total-count-info').val(totalVal)
                                        totalVal == 0 ? $('.total-notif-4').html('') : $('.total-notif-4').html(totalVal + '+');
                                    }
                                }
                            });
                        });
                    }
                });
                    break;
        }
    }

    function scanChecked(category){
        switch (category) {
            case 'Physical Security':
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'email_checked',
                        fullname: fullname,
                        category: category
                    },
                    success: function(result) {
                        var checkedNotifCount = 0;
                        let html = '';
                        var count = 0;
                        var countDetails = 0;
                        $.each(result, function(date, row) {
                            count++;
                            $.each(row, function(key, details) {
                                countDetails++;
                            });
                        });
                        $.each(result, function(date, row) {
                            $('.nav-item:eq(2)').css('display', 'block');
                            $('.checked-status').addClass('ishowmo');
                            count--;
                            let displayDate = formatDate(date); // Format the date
                            let uniq = $('.date_checked_uniq' + count).val();
                            let notExist = false;
                            if (uniq != date) {
                                html += '<tr>';
                                html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_checked_uniq' + count + '" id="date_checked_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                                html += '</tr>';
                                notExist = true;
                            }
                            $.each(row, function(key, details) {
                                countDetails--;
                                let table_name = details.table_name == 'phd_time_sync_log_header' ? 'Time Synchronization Monitoring Log Sheet' : '';
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="checked_' + displayDate + '">';
                                    html += '</tr>';
                                }
                                let uniqFrom = $('.checked_details_uniq' + countDetails).val();
                                if (uniqFrom != details.notificationid) {
                                    if(notExist){
                                        html += '<tr>';
                                    }
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="checked_details_uniq' + countDetails + '" id="checked_details_uniq' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorCheckedStatus' + countDetails + '" id="messageBehaviorCheckedStatus' + countDetails + '" value="' + details.checked_by_acknowledge + '"><input type="hidden" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + countDetails + '" id="messageBehaviorRequestStatus' + countDetails + '" value="' + details.prepared_by_acknowledge + '">' + details.remarks + '</td>';

                                    if (details.checked_by_acknowledge == false) {
                                        checkedNotifCount++;
                                        $button_color = 'btn-dark';
                                        $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                    } else {
                                        if (details.noted_by_acknowledge == true) {
                                            if (details.prepared_by_acknowledge == true) {
                                                $button_color = 'btn-warning';
                                                $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                            } else {
                                                $button_color = 'btn-info';
                                                $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                            }
                                        } else {
                                            $button_color = 'btn-info';
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        }
                                    }
                                    html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' btn-acknowledge-' + details.notificationid + ' checked-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'checked\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                    if(notExist){
                                        html += '</tr>';
                                        $('.data-checked').prepend(html);
                                    }else{
                                        var classElement = document.createElement("tr");
                                        classElement.innerHTML = html;
                                        var trId = document.getElementById("checked_" + displayDate);
                                        trId.parentNode.insertBefore(classElement, trId.nextSibling);
                                    }
                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                    $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                                    if(checkedNotifCount > 0){
                                        let checkedInc = parseInt($('#checked-count').val(), 10) + 1;
                                        $('#checked-count').val(checkedInc);
                                        checkedInc == 0 ? $('.checked-count').html('') : $('.checked-count').html(checkedInc + '+');

                                        let totalVal = parseInt($('#total-count-phd').val(), 10) + 1;
                                        $('#total-count-phd').val(totalVal)
                                        totalVal == 0 ? $('.total-notif-6').html('') : $('.total-notif-6').html(totalVal + '+');
                                    }
                                }
                            });
                        });
                    }
                })
                break;
        }
    }

    function scanReceived(category){
        switch (category) {
            case 'Info Security':
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'email_received',
                        department: department,
                        fullname: fullname,
                        category: category
                    },
                    success: function(result) {
                        var receivedNotifCount = 0;
                        let html = '';
                        var count = 0;
                        var countDetails = 0;
                        $.each(result, function(date, row) {
                            count++;
                            $.each(row, function(key, details) {
                                countDetails++;
                            });
                        });
                        $.each(result, function(date, row) {
                            $('.nav-item:eq(1)').css('display', 'block');
                            $('.received-status').addClass('ishowmo');
                            count--;
                            let displayDate = formatDate(date); // Format the date
                            let uniq = $('.date_receive_uniq' + count).val();
                            let notExist = false;
                            if (uniq != date) {
                                html += '<tr>';
                                html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_receive_uniq' + count + '" id="date_receive_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                                html += '</tr>';
                                notExist = true;
                            }
                            $.each(row, function(key, details) {
                                countDetails--;
                                let table_name = details.table_name == 'info_sec_web_app_request' ? 'Web Application' : '';
                                
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="received_' + displayDate + '">';
                                    html += '</tr>';
                                }
                                let uniqFrom = $('.receive_details_uniq' + countDetails).val();
                                if (uniqFrom != details.notificationid) {
                                    if(notExist){
                                        html += '<tr>';
                                    }
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="receive_details_uniq' + countDetails + '" id="receive_details_uniq' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorReceivedWebStatus' + countDetails + '" id="messageBehaviorReceivedWebStatus' + countDetails + '" value="' + details.received_by_acknowledge + '">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                                    
                                    if (details.received_by_acknowledge == false) {
                                        receivedNotifCount++;
                                        $button_color = 'btn-dark';
                                        $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                    } else {
                                        if (details.approved_by_acknowledge == true) {
                                            if (details.prepared_by_acknowledge == true) {
                                                $button_color = 'btn-warning';
                                                $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                            } else {
                                                $button_color = 'btn-info';
                                                $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                            }   
                                        } else {
                                            $button_color = 'btn-info';
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        }
                                    }
                                    html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' btn-acknowledge-' + details.notificationid + ' received-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'received\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                    if(notExist){
                                        html += '</tr>';
                                        $('.data-received').prepend(html);
                                    }else{
                                        var classElement = document.createElement("tr");
                                        classElement.innerHTML = html;
                                        var trId = document.getElementById("received_" + displayDate);
                                        trId.parentNode.insertBefore(classElement, trId.nextSibling);
                                    }
                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                    $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                                    if(receivedNotifCount > 0){
                                        let receivedInc = parseInt($('#received-count').val(), 10) + 1;
                                        $('#received-count').val(receivedInc);
                                        receivedInc == 0 ? $('.received-count').html('') : $('.received-count').html(receivedInc + '+');

                                        let totalVal = parseInt($('#total-count-info').val(), 10) + 1;
                                        $('#total-count-info').val(totalVal)
                                        totalVal == 0 ? $('.total-notif-4').html('') : $('.total-notif-4').html(totalVal + '+');
                                    }
                                }
                            });
                        });
                    }
                });
                break;
        }
    }

    function scanApproved(category){
        if (category == 'IT Repair and Request' || category == 'Info Security') {
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'email_approved_and_noted',
                        fullname: fullname,
                        email_type: 'approved_by',
                        category: category
                    },
                    success: function(result) {
                        var approvedNotifCount = 0;
                        let html = '';
                        var count = 0;
                        var countDetails = 0;
                        $.each(result, function(date, row) {
                            count++;
                            $.each(row, function(key, details) {
                                countDetails++;
                            });
                        });
                        $.each(result, function(date, row) {
                            count--;
                            let displayDate = formatDate(date); // Format the dat
                            let uniq = $('.date_approved_uniq' + count).val();
                            let notExist = false;
                            if (uniq != date) {
                                html += '<tr>';
                                html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_approved_uniq' + count + '" id="date_approved_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                                html += '</tr>';
                                notExist = true;
                            }
                            $.each(row, function(key, details) {
                                $('.nav-item:eq(3)').css('display', 'block');
                                $('.approved-status').addClass('ishowmo');
                                countDetails--;
                                let table_name;
                                if (details.table_name == 'tblit_repair') {
                                    table_name = 'Repair Request Main';
                                } else if (details.table_name == 'tblit_request') {
                                    table_name = 'Software and Hardware Request Main';
                                } else if (details.table_name == 'info_sec_web_app_request') {
                                    table_name = 'Web Application';
                                }
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="approved_' + displayDate + '">';
                                    html += '</tr>';
                                }
                                
                                let uniqFrom = $('.date_receive_details_uniq_' + countDetails).val();
                                if (uniqFrom != details.notificationid) {
                                    if(notExist){
                                        html += '<tr>';
                                    }
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorRepairStatus' + countDetails + '" id="messageBehaviorRepairStatus' + countDetails + '" value="' + details.repair_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + countDetails + '" id="messageBehaviorRequestStatus' + countDetails + '" value="' + details.prepared_by_acknowledge + '"><input type="hidden" class="date_receive_details_uniq_' + countDetails + '" id="date_receive_details_uniq_' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                    // html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorApprovedStatus' + countDetails + '" id="messageBehaviorApprovedStatus' + countDetails + '" value="' + details.approved_by_acknowledge + '"><input type="text" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="messageStatus' + countDetails + '" id="messageStatus' + countDetails + '" value="' + details.cancel_status + '"><input type="hidden" class="messageBehaviorApprovedStatus' + countDetails + '" id="messageBehaviorApprovedStatus' + countDetails + '" value="' + details.approved_by_acknowledge + '"><input type="hidden" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                                    if (details.approved_by_acknowledge == true) {
                                        if (details.prepared_by_acknowledge == true) {
                                            $button_color = 'btn-warning';
                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                        } else {
                                            $button_color = 'btn-info';
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        }
                                    } else {
                                        approvedNotifCount++;
                                        $button_color = 'btn-dark';
                                        $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                    }
                                    html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' approved-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'approved\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                    if(notExist){
                                        html += '</tr>';
                                        $('.data-approved').prepend(html);
                                    }else{
                                        var classElement = document.createElement("tr");
                                        classElement.innerHTML = html;
                                        var trId = document.getElementById("approved_" + displayDate);
                                        trId.parentNode.insertBefore(classElement, trId.nextSibling);
                                    }
                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                    $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                                    if (category == 'Info Security') {
                                        if(approvedNotifCount > 0){
                                            let approvedInc = parseInt($('#approved-count').val(), 10) + 1;
                                            $('#approved-count').val(approvedInc);
                                            approvedInc == 0 ? $('.approved-count').html('') : $('.approved-count').html(approvedInc + '+');
    
                                            let totalVal = parseInt($('#total-count-info').val(), 10) + 1;
                                            $('#total-count-info').val(totalVal)
                                            totalVal == 0 ? $('.total-notif-4').html('') : $('.total-notif-4').html(totalVal + '+');
                                        }
                                    } else {
                                        if(approvedNotifCount > 0){
                                            let approvedInc = parseInt($('#approved-count').val(), 10) + 1;
                                            $('#approved-count').val(approvedInc);
                                            approvedInc == 0 ? $('.approved-count').html('') : $('.approved-count').html(approvedInc + '+');
    
                                            let totalVal = parseInt($('#total-count').val(), 10) + 1;
                                            $('#total-count').val(totalVal)
                                            totalVal == 0 ? $('.total-notif-8').html('') : $('.total-notif-8').html(totalVal + '+');
                                        }
                                    }
                                }
                            });
                        });
                    }
                });

        }
    }

    function scanNoted(category){
        if (category == 'IT Repair and Request' || category == 'Info Security') {
            $.ajax({
                url: '../controller/notification_controller/notification_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'email_approved_and_noted',
                    fullname: fullname,
                    email_type: 'noted_by',
                    category: category
                },
                success: function(result) {
                    var notedNotifCount = 0;
                    let html = '';
                    var count = 0;
                    var countDetails = 0;
                    $.each(result, function(date, row) {
                        count++;
                        $.each(row, function(key, details) {
                            countDetails++;
                        });
                    });
                    $.each(result, function(date, row) {
                        $('.nav-item:eq(4)').css('display', 'block');
                        $('.noted-status').addClass('ishowmo');
                        count--;
                        let displayDate = formatDate(date); // Format the date
                        let uniq = $('.date_noted_uniq' + count).val();
                        let notExist = false;
                        if (uniq != date) {
                            html += '<tr>';
                            html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_noted_uniq' + count + '" id="date_noted_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                            html += '</tr>';
                            notExist = true;
                        }
                        $.each(row, function(key, details) {
                            countDetails--;
                            let table_name;
                            if (details.table_name == 'tblit_repair') {
                                table_name = 'Repair Request Main';
                            } else if (details.table_name == 'tblit_request') {
                                table_name = 'Software and Hardware Request Main';
                            } else if (details.table_name == 'info_sec_web_app_request') {
                                table_name = 'Web Application';
                            }
                            if (key === 0) {
                                html += '<tr class="hide-tr" id="noted_' + displayDate + '">';
                                html += '</tr>';
                            }
                            let uniqFrom = $('.note_details_uniq_' + countDetails).val();
                            
                            if (uniqFrom != details.notificationid) {
                                if(notExist){
                                    html += '<tr>';
                                }
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="note_details_uniq_' + countDetails + '" id="note_details_uniq_' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="messageStatus' + countDetails + '" id="messageStatus' + countDetails + '" value="' + details.cancel_status + '"><input type="hidden" class="messageBehaviorApprovedStatus' + countDetails + '" id="messageBehaviorApprovedStatus' + countDetails + '" value="' + details.approved_by_acknowledge + '"><input type="hidden" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                                if (details.noted_by_acknowledge == true) {
                                    if (details.prepared_by_acknowledge == true) {
                                        $button_color = 'btn-warning';
                                        $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                    } else {
                                        $button_color = 'btn-info';
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    }
                                } else {
                                    if (details.approved_by_acknowledge == false) {
                                        $button_color = 'btn-warning';
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    } else {
                                        notedNotifCount++;
                                        $button_color = 'btn-dark';
                                        $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                    }
                                }
                                html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' noted-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'noted\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                if(notExist){
                                    html += '</tr>';
                                    $('.data-noted').prepend(html);
                                }else{
                                    var classElement = document.createElement("tr");
                                    classElement.innerHTML = html;
                                    var trId = document.getElementById("noted_" + displayDate);
                                    trId.parentNode.insertBefore(classElement, trId.nextSibling);
                                }
                                $('[data-bs-toggle="tooltip"]').tooltip();
                                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                                if (category == 'Info Security') {
                                    if(notedNotifCount > 0){
                                        let notedInc = parseInt($('#noted-count').val(), 10) + 1;
                                        $('#noted-count').val(notedInc);
                                        notedInc == 0 ? $('.noted-count').html('') : $('.noted-count').html(notedInc + '+');

                                        let totalVal = parseInt($('#total-count-info').val(), 10) + 1;
                                        $('#total-count-info').val(totalVal)
                                        totalVal == 0 ? $('.total-notif-4').html('') : $('.total-notif-4').html(totalVal + '+');
                                    }
                                } else {
                                    if(notedNotifCount > 0){
                                        let notedInc = parseInt($('#noted-count').val(), 10) + 1;
                                        $('#noted-count').val(notedInc);
                                        notedInc == 0 ? $('.noted-count').html('') : $('.noted-count').html(notedInc + '+');

                                        let totalVal = parseInt($('#total-count').val(), 10) + 1;
                                        $('#total-count').val(totalVal)
                                        totalVal == 0 ? $('.total-notif-8').html('') : $('.total-notif-8').html(totalVal + '+');
                                    }
                                }
                            }
                        });
                    });
                }
            });
        } else if (category == 'Physical Security') {
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'email_approved_and_noted',
                        fullname: fullname,
                        email_type: 'noted_by',
                        category: category
                    },
                    success: function(result) {
                        var notedPhdCount = 0;
                        let html = '';
                        var count = 0;
                        var countDetails = 0;
                        $.each(result, function(date, row) {
                            count++;
                            $.each(row, function(key, details) {
                                countDetails++;
                            });
                        });
                        $.each(result, function(date, row) {
                            $('.nav-item:eq(4)').css('display', 'block');
                            $('.noted-status').addClass('ishowmo');
                            count--;
                            let displayDate = formatDate(date); // Format the date
                            let uniq = $('.date_noted_uniq' + count).val();
                            let notExist = false;
                            if (uniq != date) {
                                html += '<tr>';
                                html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_noted_uniq' + count + '" id="date_noted_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                                html += '</tr>';
                                notExist = true;
                            }
                            $.each(row, function(key, details) {
                                countDetails--;
                                let table_name = details.table_name == 'phd_time_sync_log_header' ? 'Time Synchronization Monitoring Log Sheet' : '';
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="noted_phd' + displayDate + '">';
                                    html += '</tr>';
                                }
                                let uniqFrom = $('.note_details_uniq_' + countDetails).val();
                                if (uniqFrom != details.notificationid) {
                                    if(notExist){
                                        html += '<tr>';
                                    }
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="note_details_uniq_' + countDetails + '" id="note_details_uniq_' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                    html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorCheckedStatus' + countDetails + '" id="messageBehaviorCheckedStatus' + countDetails + '" value="' + details.approved_by_acknowledge + '"><input type="hidden" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '">' + details.remarks + '</td>';
                                    if (details.noted_by_acknowledge == true) {
                                        if (details.prepared_by_acknowledge == true) {
                                            $button_color = 'btn-warning';
                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                        } else {
                                            $button_color = 'btn-info';
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        }
                                    } else {
                                        if (details.checked_by_acknowledge == false) {
                                            $button_color = 'btn-warning';
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        } else {
                                            notedPhdCount++;
                                            $button_color = 'btn-dark';
                                            $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                        }
                                    }
                                    html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' noted-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'noted\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                    if(notExist){
                                        html += '</tr>';
                                        $('.data-noted').prepend(html);
                                    }else{
                                        var classElement = document.createElement("tr");
                                        classElement.innerHTML = html;
                                        var trId = document.getElementById("noted_phd" + displayDate);
                                        trId.parentNode.insertBefore(classElement, trId.nextSibling);
                                    }
                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                    $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                                    if(notedPhdCount > 0){
                                        let notedInc = parseInt($('#noted-count').val(), 10) + 1;
                                        $('#noted-count').val(notedInc);
                                        notedInc == 0 ? $('.noted-count').html('') : $('.noted-count').html(notedInc + '+');

                                        let totalVal = parseInt($('#total-count-phd').val(), 10) + 1;
                                        $('#total-count-phd').val(totalVal)
                                        totalVal == 0 ? $('.total-notif-6').html('') : $('.total-notif-6').html(totalVal + '+');
                                    }
                                }
                            });
                        });
                    }
                });
        }
    }

    function buttonScanning(category) {
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'scanBtnItr',
                fullname: fullname,
                category: category
            },
            success: function(result) {
                var count = 0;
                var countDetails = 0;
                $.each(result, function(date, row) {
                    count++;
                    $.each(row, function(key, details) {
                        countDetails++;
                    });
                });
                $.each(result, function(date, row) {
                    count--;
                    $.each(row, function(key, details) {
                        countDetails--;
                        let reqvalue = {};
                        let notedvalue = {};
                        if(details.table_database == 'it_repair_request'){
                            reqvalue = {
                                acknowledge1: details.repair_by_acknowledge,
                                acknowledge2: details.prepared_by_acknowledge, 
                                status1: 'messageBehaviorRepairStatus',
                                status2: 'messageBehaviorRequestStatus',
                                existacknowledge: 'repair-acknowledge-',
                                navcount: 'req-count',
                                totalcount: 'total-count',
                                totalnotif: 'total-notif-8'
                            };
                            // ! ###### Request Side #######
                            notedvalue = {
                                acknowledge1: details.approved_by_acknowledge,
                                acknowledge2: details.noted_by_acknowledge, 
                                acknowledge3: details.prepared_by_acknowledge, 
                                name1: details.approved_by,
                                name2: details.noted_by, 
                                status1: 'messageBehaviorApprovedStatus',
                                status2: 'messageBehaviorNotedStatus',
                                cancelStatusExist: details.cancel_status,
                                cancelStatus: 'messageStatus',
                                navcount1: 'approved-count',
                                navcount2: 'noted-count',
                                totalcount: 'total-count',
                                totalnotif: 'total-notif-8',
                                existacknowledge: 'approved-acknowledge-',
                                existacknowledge2: 'noted-acknowledge-'
                            };
                            // ! ###### Approved And Noted Side #######
                        }else if(details.table_database == 'physical_security'){
                            reqvalue = {
                                acknowledge1: details.noted_by_acknowledge,
                                acknowledge2: details.prepared_by_acknowledge, 
                                status1: 'messageBehaviorNotedStatus',
                                status2: 'messageBehaviorRequestStatus',
                                existacknowledge: 'request-acknowledge-',
                                navcount: 'req-count',
                                totalcount: 'total-count-phd',
                                totalnotif: 'total-notif-6'
                            };
                            notedvalue = {
                                acknowledge1: details.checked_by_acknowledge,
                                acknowledge2: details.noted_by_acknowledge, 
                                acknowledge3: details.prepared_by_acknowledge, 
                                name1: details.checked_by,
                                name2: details.noted_by, 
                                status1: 'messageBehaviorCheckedStatus',
                                status2: 'messageBehaviorNotedStatus',
                                cancelStatusExist: details.cancel_status,
                                cancelStatus: 'messageStatus',
                                navcount1: 'checked-count',
                                navcount2: 'noted-count',
                                totalcount: 'total-count-phd',
                                totalnotif: 'total-notif-6',
                                existacknowledge: 'checked-acknowledge-',
                                existacknowledge2: 'noted-acknowledge-'
                            };
                        }else if(details.table_database == 'info_security'){
                            reqvalue = {
                                acknowledge1: details.repair_by_acknowledge,
                                acknowledge2: details.prepared_by_acknowledge, 
                                status1: 'messageBehaviorRepairStatus',
                                status2: 'messageBehaviorRequestStatus',
                                existacknowledge: 'request-acknowledge-',
                                navcount: 'req-count',
                                totalcount: 'total-count-info',
                                totalnotif: 'total-notif-4'
                            };
                            // ! ###### Request Side #######
                            notedvalue = {
                                acknowledge1: details.approved_by_acknowledge,
                                acknowledge2: details.noted_by_acknowledge, 
                                acknowledge3: details.prepared_by_acknowledge, 
                                name1: details.approved_by,
                                name2: details.noted_by, 
                                status1: 'messageBehaviorApprovedStatus',
                                status2: 'messageBehaviorNotedStatus',
                                cancelStatusExist: details.cancel_status,
                                cancelStatus: 'messageStatus',
                                navcount1: 'approved-count',
                                navcount2: 'noted-count',
                                totalcount: 'total-count-info',
                                totalnotif: 'total-notif-4',
                                existacknowledge: 'approved-acknowledge-',
                                existacknowledge2: 'noted-acknowledge-'
                            };
                            // ! ###### Approved And Noted Side #######
                        }
                        let cancelStatusExist = $('#'+notedvalue.cancelStatus + countDetails).val();
                        let cancelStatusLive = notedvalue.cancelStatusExist + '';
                        if(cancelStatusExist!=cancelStatusLive){
                            if(notedvalue.cancelStatusExist == true){
                                $('.'+reqvalue.existacknowledge + details.notificationid).html('<i class="fa-solid fa-ban fa-shake"></i>');
                                $('.'+reqvalue.existacknowledge + details.notificationid).removeClass('btn-dark').addClass('btn-danger');
    
                                $('.'+notedvalue.existacknowledge + details.notificationid).html('<i class="fa-solid fa-ban fa-shake"></i>');
                                $('.'+notedvalue.existacknowledge + details.notificationid).removeClass('btn-dark').addClass('btn-danger');

                                $('.'+notedvalue.existacknowledge2 + details.notificationid).html('<i class="fa-solid fa-ban fa-shake"></i>');
                                $('.'+notedvalue.existacknowledge2 + details.notificationid).removeClass('btn-dark').addClass('btn-danger');

                                let approved_count = parseInt($('#'+notedvalue.navcount1).val(), 10);
                                if (approved_count <= 0) {
                                    $('.'+notedvalue.navcount1).html('');
                                } else {
                                    let total_approved_count = approved_count - 1;
                                    $('#'+notedvalue.navcount1).val(total_approved_count);
                                    total_approved_count == 0 ? $('.'+notedvalue.navcount1).html('') : $('.'+notedvalue.navcount1).html(total_checked_count + '+');
                                }
                                // ? Total Count Calculation
                                let totalVal = parseInt($('#'+notedvalue.totalcount).val(), 10) - 1;
                                $('#'+notedvalue.totalcount).val(totalVal);
                                totalVal == 0 ? $('.'+notedvalue.totalnotif).html('') : $('.'+notedvalue.totalnotif).html(totalVal + '+');
                            }
                        }else{
                            // ! ###### Request Side #######
                            let noted = $('#'+reqvalue.status1 + countDetails).val();
                            let req = $('#'+reqvalue.status2 + countDetails).val();
                            let onloadReq = noted + req;
                            let scanloadReq = reqvalue.acknowledge1 + '' + reqvalue.acknowledge2;
                            if (onloadReq != scanloadReq) {
                                if (reqvalue.acknowledge1 == true && reqvalue.acknowledge2 == false) {
                                    let req_count = parseInt($('#'+reqvalue.navcount).val(), 10);
                                    let total_req_count = req_count + 1;
                                    $('#'+reqvalue.navcount).val(total_req_count);
                                    total_req_count == 0 ? $('.'+reqvalue.navcount).html('') : $('.'+reqvalue.navcount).html(total_req_count + '+');

                                    // ? Total Count Calculation
                                    let totalVal = parseInt($('#'+reqvalue.totalcount).val(), 10) + 1;
                                    $('#'+reqvalue.totalcount).val(totalVal)
                                    totalVal == 0 ? $('.'+reqvalue.totalnotif).html('') : $('.'+reqvalue.totalnotif).html(totalVal + '+');
                                    $('.'+reqvalue.existacknowledge + details.notificationid).html('<i class="fa-regular fa-envelope fa-shake"></i>');
                                    $('.'+reqvalue.existacknowledge + details.notificationid).removeClass('btn-info').addClass('btn-dark');

                                } else if (reqvalue.acknowledge1 == true && reqvalue.acknowledge2 == true) {
                                    let req_count = parseInt($('#'+reqvalue.navcount).val(), 10);
                                    let total_req_count = req_count - 1;
                                    $('#'+reqvalue.navcount).val(total_req_count);
                                    total_req_count == 0 ? $('.'+reqvalue.navcount).html('') : $('.'+reqvalue.navcount).html(total_req_count + '+');

                                    // ? Total Count Calculation
                                    let totalVal = parseInt($('#'+reqvalue.totalcount).val(), 10) - 1;
                                    $('#total-count').val(totalVal);
                                    totalVal == 0 ? $('.'+reqvalue.totalnotif).html('') : $('.'+reqvalue.totalnotif).html(totalVal + '+');
                                    $('.'+reqvalue.existacknowledge + details.notificationid).html('<i class="fa-solid fa-envelope-open-text fa-fade"></i>');
                                    $('.'+reqvalue.existacknowledge + details.notificationid).removeClass('btn-dark').addClass('btn-warning');
                                }
                            }

                            // ! ###### Checked And Noted Side #######
                            let chec = $('#'+notedvalue.status1 + countDetails).val();
                            let note = $('#'+notedvalue.status2 + countDetails).val();
                            let onload = chec + note;
                            let scanload = notedvalue.acknowledge1 + '' + notedvalue.acknowledge2;
                            console.log(scanload + onload);
                            if (onload != scanload) {
                                if (notedvalue.acknowledge1 == true && notedvalue.acknowledge2 == false) {
                                    if (notedvalue.name1 == fullname && notedvalue.name2 == fullname) {
                                        let totalCheckedCount = parseInt($('#'+notedvalue.totalcount).val(), 10) - 1;
                                        $('#'+notedvalue.totalcount).val(totalCheckedCount)
                                        totalCheckedCount == 0 ? $('.'+notedvalue.totalnotif).html('') : $('.'+notedvalue.totalnotif).html(totalCheckedCount + '+');
                                        $('.'+notedvalue.existacknowledge + details.notificationid).html('<i class="fa-solid fa-circle-info fa-bounce"></i>');
                                        $('.'+notedvalue.existacknowledge + details.notificationid).removeClass('btn-dark').addClass('btn-info');
    
                                        let checked_count = parseInt($('#'+notedvalue.navcount1).val(), 10);
                                        if (checked_count <= 0) {
                                            $('.'+notedvalue.navcount1).html('');
                                        } else {
                                            let total_checked_count = checked_count - 1;
                                            $('#'+notedvalue.navcount1).val(total_checked_count);
                                            total_checked_count == 0 ? $('.'+notedvalue.navcount1).html('') : $('.'+notedvalue.navcount1).html(total_checked_count + '+');
                                        }
    
                                        let note_count = parseInt($('#'+notedvalue.navcount2).val(), 10);
                                        let total_note_count = note_count + 1;
                                        $('#'+notedvalue.navcount2).val(total_note_count);
                                        total_note_count == 0 ? $('.'+notedvalue.navcount2).html('') : $('.'+notedvalue.navcount2).html(total_note_count + '+');
                                        // ? Total Count Calculation
                                        let totalVal = parseInt($('#'+notedvalue.totalcount).val(), 10) + 1;
                                        $('#'+notedvalue.totalcount).val(totalVal)
                                        totalVal == 0 ? $('.'+notedvalue.totalnotif).html('') : $('.'+notedvalue.totalnotif).html(totalVal + '+');
    
                                    } else if (notedvalue.name2 == fullname) {
                                        let note_count = parseInt($('#'+notedvalue.navcount2).val(), 10);
                                        let total_note_count = note_count + 1;
                                        $('#'+notedvalue.navcount2).val(total_note_count);
                                        total_note_count == 0 ? $('.'+notedvalue.navcount2).html('') : $('.'+notedvalue.navcount2).html(total_note_count + '+');
                                        // ? Total Count Calculation
                                        let totalVal = parseInt($('#'+notedvalue.totalcount).val(), 10) + 1;
                                        $('#'+notedvalue.totalcount).val(totalVal)
                                        totalVal == 0 ? $('.'+notedvalue.totalnotif).html('') : $('.'+notedvalue.totalnotif).html(totalVal + '+');
                                    } else if (notedvalue.name1 == fullname) {
                                        
                                        let checked_count = parseInt($('#'+notedvalue.navcount1).val(), 10);
                                        if (checked_count <= 0) {
                                            $('.'+notedvalue.navcount1).html('');
                                        } else {
                                            let total_checked_count = checked_count - 1;
                                            $('#'+notedvalue.navcount1).val(total_checked_count);
                                            total_checked_count == 0 ? $('.'+notedvalue.navcount1).html('') : $('.'+notedvalue.navcount1).html(total_checked_count + '+');
                                        }
                                        // ? Total Count Calculation
                                        let totalCheckedCount = parseInt($('#'+notedvalue.totalcount).val(), 10) - 1;
                                        $('#'+notedvalue.totalcount).val(totalCheckedCount)
                                        totalCheckedCount == 0 ? $('.'+notedvalue.totalnotif).html('') : $('.'+notedvalue.totalnotif).html(totalCheckedCount + '+');
                                        $('.'+notedvalue.existacknowledge + details.notificationid).html('<i class="fa-solid fa-circle-info fa-bounce"></i>');
                                        $('.'+notedvalue.existacknowledge + details.notificationid).removeClass('btn-dark').addClass('btn-info');
                                    }
                                    $('.'+notedvalue.existacknowledge2 + details.notificationid).html('<i class="fa-regular fa-envelope fa-shake"></i>');
                                    $('.'+notedvalue.existacknowledge2 + details.notificationid).removeClass('btn-warning').addClass('btn-dark');
    
                                } else if (notedvalue.acknowledge1 == true && notedvalue.acknowledge2 == true) {
                                    if (notedvalue.name1 == fullname && notedvalue.name2 == fullname) {
                                        let note_count = parseInt($('#'+notedvalue.navcount2).val(), 10);
                                        let total_note_count = note_count - 1;
                                        $('#'+notedvalue.navcount2).val(total_note_count);
                                        total_note_count == 0 ? $('.'+notedvalue.navcount2).html('') : $('.'+notedvalue.navcount2).html(total_note_count + '+');
                                        // ? Total Count Calculation
                                        let totalVal = parseInt($('#'+notedvalue.totalcount).val(), 10) - 1;
                                        $('#'+notedvalue.totalcount).val(totalVal);
                                        totalVal == 0 ? $('.'+notedvalue.totalnotif).html('') : $('.'+notedvalue.totalnotif).html(totalVal + '+');
    
                                    } else if (notedvalue.name2 == fullname) {
                                        let note_count = parseInt($('#'+notedvalue.navcount2).val(), 10);
                                        let total_note_count = note_count - 1;
                                        $('#'+notedvalue.navcount2).val(total_note_count);
                                        total_note_count == 0 ? $('.'+notedvalue.navcount2).html('') : $('.'+notedvalue.navcount2).html(total_note_count + '+');
                                        // ? Total Count Calculation
                                        let totalVal = parseInt($('#'+notedvalue.totalcount).val(), 10);
                                        let totalValCount = totalVal - 1;
                                        $('#'+notedvalue.totalcount).val(totalValCount);
                                        totalValCount == 0 ? $('.'+notedvalue.totalnotif).html('') : $('.'+notedvalue.totalnotif).html(totalValCount + '+');
                                    }
                                }
                            }
                            if (notedvalue.acknowledge1 == false && notedvalue.acknowledge2 == false) {
                            if(notedvalue.cancelStatusExist == true){
                                $('.'+notedvalue.existacknowledge2 + details.notificationid).html('<i class="fa-solid fa-ban fa-shake"></i>');
                                $('.'+notedvalue.existacknowledge2 + details.notificationid).removeClass('btn-dark').addClass('btn-danger');
                            }else{
                                $('.'+notedvalue.existacknowledge2 + details.notificationid).html('<i class="fa-solid fa-circle-info fa-bounce"></i>');
                                $('.'+notedvalue.existacknowledge2 + details.notificationid).removeClass('btn-dark').addClass('btn-warning');
                            }
                            } else if (notedvalue.acknowledge1 == true && notedvalue.acknowledge2 == true) {
                                if(notedvalue.cancelStatusExist == true){
                                    $('.'+notedvalue.existacknowledge2 + details.notificationid).html('<i class="fa-solid fa-ban fa-shake"></i>');
                                    $('.'+notedvalue.existacknowledge2 + details.notificationid).removeClass('btn-dark').addClass('btn-danger');
                                }else{
                                    $('.'+notedvalue.existacknowledge2 + details.notificationid).html('<i class="fa-solid fa-circle-info fa-bounce"></i>');
                                    $('.'+notedvalue.existacknowledge2 + details.notificationid).removeClass('btn-dark').addClass('btn-info');
                                }
                            }
                            $('#'+reqvalue.status1 + countDetails).val(reqvalue.acknowledge1);
                            $('#'+reqvalue.status2 + countDetails).val(reqvalue.acknowledge2);
    
                            $('#messageBehaviorRequestStatus' + countDetails).val(details.prepared_by_acknowledge);
                            $('#'+notedvalue.status1 + countDetails).val(notedvalue.acknowledge1);
                            $('#'+notedvalue.status2 + countDetails).val(notedvalue.acknowledge2);
                        }
                        $('#'+notedvalue.cancelStatus + countDetails).val(notedvalue.cancelStatusExist);
                    });
                });
            }
        });
    }
