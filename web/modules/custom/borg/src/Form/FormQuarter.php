<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class FormQuarter extends FormBase {

  public function getFormId() {
    return 'quarter';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $year = date('Y');

    $header = [
      'year' => t('Year'),
      'jan' => t('Jan'),
      'feb' => t('Feb'),
      'mar' => t('Mar'),
      'q1' => t('Q1'),
      'apr' => t('Apr'),
      'may' => t('May'),
      'jun' => t('Jun'),
      'q2' => t('Q2'),
      'jul' => t('Jul'),
      'aug' => t('Aug'),
      'sep' => t('Sep'),
      'q3' => t('Q3'),
      'oct' => t('Oct'),
      'nov' => t('Nov'),
      'dec' => t('Dec'),
      'q4' => t('Q4'),
      'ytd' => t('YTD'),
    ];

    $value = [];
//    foreach ($form as $value) {
    $value['year'] = [
        '#markup' => $year,
      ];
    $value['jan'] = [
        '#type' => 'number',
      ];
    $value['feb'] = [
        '#type' => 'number',
      ];
    $value['mar'] = [
        '#type' => 'number',
      ];
    $value['q1'] = [
        '#type' => 'number',
      ];
    $value['apr'] = [
        '#type' => 'number',
      ];
    $value['may'] = [
        '#type' => 'number',
      ];
    $value['jun'] = [
        '#type' => 'number',
      ];
    $value['q2'] = [
        '#type' => 'number',
      ];
    $value['jul'] = [
        '#type' => 'number',
      ];
    $value['aug'] = [
        '#type' => 'number',
      ];
    $value['sep'] = [
        '#type' => 'number',
      ];
    $value['q3'] = [
        '#type' => 'number',
      ];
    $value['oct'] = [
        '#type' => 'number',
      ];
    $value['nov'] = [
        '#type' => 'number',
      ];
    $value['dec'] = [
        '#type' => 'number',
      ];
    $value['q4'] = [
        '#type' => 'number',
      ];
    $value['ytd'] = [
        '#markup' => 'add',
      ];
//      $rows[] = $value;
//    }

    $form['table'] = [
      '#type' => 'table',
      '#header' => $header,
//      '#rows' => [
//        0 => $value,
//      ],
//      $value,
    ];
    $form['table'][] = $value;
//    $form['submit'] = [
//      '#type' => 'submit',
//      '#value' => $this->t('Delete all selected'),
//    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
//    $value = $form['table']['#value'];
//    $connect = Database::getConnection();
//    $i = 0;
//    $fid = [];
//    foreach ($value as $key) {
//      $output = $connect->select('borg', 'x')
//        ->fields('x', ['id'])
//        ->condition('id', $form['table']['#options'][$key]['id'])
//        ->execute();
//      $fid[$i] = $output->fetchAssoc();
//      $i += 1;
//    }
//    $_SESSION['del_id'] = $fid;
//    $form_state->setRedirect('borg.delete_all_form');
  }

}
