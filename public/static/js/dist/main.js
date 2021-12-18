$(function () {

  'use strict';

  var $distpicker = $('#distpicker');

  $distpicker.distpicker({
    province: '國家或地區',
    city: '縣市區域',
    district: '鄉鎮地區'
  });

  $('#reset').click(function () {
    $distpicker.distpicker('reset');
  });

  $('#reset-deep').click(function () {
    $distpicker.distpicker('reset', true);
  });

  $('#destroy').click(function () {
    $distpicker.distpicker('destroy');
  });

  $('#distpicker1').distpicker({
    province: '國家或地區',
    city: '縣市區域',
    district: '鄉鎮地區'
  });

  $('#distpicker2').distpicker({
    province: '國家或地區',
    city: '縣市區域',
    district: '鄉鎮地區'
  });

  $('#distpicker3').distpicker({
    province: '國家或地區',
    city: '縣市區域',
    district: '鄉鎮地區'
  });

  $('#distpicker4').distpicker({
    placeholder: false
  });

  $('#distpicker5').distpicker({
    autoSelect: false
  });

});
