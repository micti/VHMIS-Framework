<?php

/**
 * Calendar
 *
 * Truy xuất lấy dữ liệu về lịch
 *
 * PHP 5
 *
 * VHMIS(tm) : Viethan IT Management Information System
 * Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 *
 * All rights reversed, giữ toàn bộ bản quyền, các thư viện bên ngoài xin xem file thông tin đi kèm
 *
 * @copyright Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 * @link https://github.com/VHIT/VHMIS VHMIS(tm) Project
 * @category VHMIS
 * @package Component
 * @subpackage Entity
 * @since 1.0.0
 * @license All rights reversed
 */

/**
 */
class Vhmis_Component_Calendar extends Vhmis_Component
{

    public function init()
    {
        // Kết nối CSDL
        $db = $this->_db('Office');
        $this->_model = new Vhmis_Model_Office_Calendar(array(
            'db' => $db
        ));
    }

    public function findEvents($start, $end, $user, $returnType)
    {
        $events = $this->_model->getAllInRange($start, $end, $user['id'], $user['hrm_id_department']);
        $calendars = array();
        
        // Ngày
        $dateO = new Vhmis_Date();
        
        foreach ($events as $event) {
            $event = $this->_model->toArray($event);
            
            $invite = array();
            if ($event['invite_name_department'] != '')
                $invite[] = $event['invite_name_department'];
            if ($event['invite_name_group'] != '')
                $invite[] = $event['invite_name_group'];
            if ($event['invite_name_individual'] != '')
                $invite[] = $event['invite_name_individual'];
            $invite = implode($invite, ' ; ');
            $notInvite = $event['invite_name_not_individual'];
            $prepare = $event['prepare_name_department'];
            $date = explode(' ', $event['time_start']);
            $time = substr($date[1], 0, 5);
            $timeHourRange = current(explode(':', $time)) . ':00'; // Mốc giờ
                                                                   // bắt đầu
            $date = $date[0];
            $related = false;
            
            // Lấy thông tin so với ngày hôm nay
            $dateO->time($date);
            $relatedToday = $dateO->relatedToday();
            
            // Kiểm tra độ liên quan
            if ($event['type'] == 0 || $event['type'] == 1 || $event['type'] == 2)
                $related = true;
            else {
                if (strpos(',' . $event['invite_not_individual'] . ',', ',' . $user['id'] . ',') === false) {
                    if (strpos(',' . $event['invite_department'] . ',', ',' . $user['hrm_id_department'] . ',') !== false) {
                        $related = true;
                    } else 
                        if (strpos(',' . $event['invite_individual'] . ',', ',' . $user['id'] . ',') !== false) {
                            $related = true;
                        } else {
                            // $groups = explode(',', $this->user['groups']);
                            if (is_array($user['groups'])) {
                                foreach ($user['groups'] as $group) {
                                    if ($group != '') {
                                        if (strpos(',' . $event['invite_group'] . ',', ',' . $group . ',') !== false) {
                                            $related = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                }
            }
            
            $eventDetail = array(
                'id' => $event['id'],
                'name_user' => $event['name_user'],
                'id_user' => $event['id_user'],
                'date' => $date,
                'time' => $time,
                'title' => $event['title'],
                'invite' => $invite,
                'not_invite' => $notInvite,
                'prepare' => $prepare,
                'note' => $event['note'],
                'address' => $event['address'],
                'type' => $event['type'],
                'type_name' => $event['type_name'],
                'related' => $related,
                'relatedToday' => $relatedToday
            );
            
            // Sự kiện repeat
            if ($event['repeat'] == 1) {
                // Nếu sự kiện gốc còn nằm trong khoảng thì cho vào sự kiện gốc
                // vào danh mục sự kiện
                $dateEvent = new Vhmis_Date();
                $dateEvent->time($date);
                $dateO->time($start);
                
                if ($dateEvent->differentDay($dateO) <= 0) {
                    $dateO->time($end);
                    if ($dateEvent->differentDay($dateO) >= 0) {
                        $inrange = true;
                    } else {
                        $inrange = false;
                    }
                } else {
                    $inrange = false;
                }
                
                if ($inrange) {
                    if ($returnType == 'daily' || $returnType == 'weekly') {
                        $calendars[$date][$timeHourRange][$time]['id_' . $event['id']] = $eventDetail;
                    } else {
                        $calendars[$date][$time]['id_' . $event['id']] = $eventDetail;
                    }
                }
                
                $repeatedDates = $this->findRepeatedDate($date, $start, $end, $event);
                
                foreach ($repeatedDates as $repeatedDate) {
                    $dateO->time($repeatedDate);
                    
                    if ($returnType == 'daily' || $returnType == 'weekly') {
                        $calendars[$repeatedDate][$timeHourRange][$time]['id_' . $event['id']] = $eventDetail;
                        $calendars[$repeatedDate][$timeHourRange][$time]['id_' . $event['id']]['date'] = $repeatedDate;
                        $calendars[$repeatedDate][$timeHourRange][$time]['id_' . $event['id']]['relatedToday'] = $dateO->relatedToday();
                    } else {
                        $calendars[$repeatedDate][$time]['id_' . $event['id']] = $eventDetail;
                        $calendars[$repeatedDate][$time]['id_' . $event['id']]['date'] = $repeatedDate;
                        $calendars[$repeatedDate][$time]['id_' . $event['id']]['relatedToday'] = $dateO->relatedToday();
                    }
                }
            } else {
                if ($returnType == 'daily' || $returnType == 'weekly') {
                    $calendars[$date][$timeHourRange][$time]['id_' . $event['id']] = $eventDetail;
                } else {
                    $calendars[$date][$time]['id_' . $event['id']] = $eventDetail;
                }
            }
        }
        
        // Sắp xếp lại mảng $calendar
        ksort($calendars);
        foreach ($calendars as &$dates) {
            ksort($dates);
            
            if ($returnType == 'daily' || $returnType == 'weekly') {
                foreach ($dates as &$times) {
                    ksort($times);
                }
            }
        }
        
        return $calendars;
    }

    public function findRepeatedDate($baseDate, $startDate, $endDate, $event)
    {
        $dateRepeat = new Vhmis_Date_Repeat($baseDate, $startDate, $endDate);
        
        $repeatedDates = array();
        
        if ($event['repeat_unit'] == 4) {
            $repeatedDates = $dateRepeat->calculateDailyRepeat(
                array(
                    'freq' => $event['repeat_freq']
                ));
        } elseif ($event['repeat_unit'] == 5) {
            $repeatedDates = $dateRepeat->calculateWeeklyRepeat(
                array(
                    'freq' => $event['repeat_freq'],
                    'weekday' => explode(',', $event['repeat_weekday'])
                ));
        } elseif ($event['repeat_unit'] == 6) {
            $repeatedDates = $dateRepeat->calculateMonthlyRepeat(
                array(
                    'freq' => $event['repeat_freq'],
                    'type' => $event['repeat_type'],
                    'monthday' => explode(',', $event['repeat_monthday']),
                    'weekday' => $event['repeat_weekday'],
                    'weekday_position' => $event['repeat_weekday_position']
                ));
        } elseif ($event['repeat_unit'] == 7) {
            $repeatedDates = $dateRepeat->calculateYearlyRepeat(
                array(
                    'freq' => $event['repeat_freq'],
                    'type' => $event['repeat_type'],
                    'month' => explode(',', $event['repeat_month']),
                    'monthday' => explode(',', $event['repeat_monthday']),
                    'weekday' => $event['repeat_weekday'],
                    'weekday_position' => $event['repeat_weekday_position']
                ));
        }
        
        return $repeatedDates;
    }
}