<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class FormQuarter extends FormBase {

  public function getFormId() {
    return 'quarter';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

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

//    $rows = [];
//    foreach ($form as $value) {
//    $form['year'] = [
//        '#type' => 'integer',
//        '#value' => 2000,
//      ];
//    $form['jan'] = [
//        '#type' => 'integer',
//      ];
//    $form['feb'] = [
//        '#type' => 'integer',
//      ];
//    $form['mar'] = [
//        '#type' => 'integer',
//      ];
//    $form['q1'] = [
//        '#type' => 'integer',
//      ];
//    $form['apr'] = [
//        '#type' => 'integer',
//      ];
//    $form['may'] = [
//        '#type' => 'integer',
//      ];
//    $form['jun'] = [
//        '#type' => 'integer',
//      ];
//    $form['q2'] = [
//        '#type' => 'integer',
//      ];
//    $form['jul'] = [
//        '#type' => 'integer',
//      ];
//    $form['aug'] = [
//        '#type' => 'integer',
//      ];
//    $form['sep'] = [
//        '#type' => 'integer',
//      ];
//    $form['q3'] = [
//        '#type' => 'integer',
//      ];
//    $form['oct'] = [
//        '#type' => 'integer',
//      ];
//    $form['nov'] = [
//        '#type' => 'integer',
//      ];
//    $form['dec'] = [
//        '#type' => 'integer',
//      ];
//    $form['q4'] = [
//        '#type' => 'integer',
//      ];
//    $form['ytd'] = [
//        '#type' => 'markup',
//        '#markup' => 'add',
//      ];
//      $rows[] = $value;
//    }

    $form['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $form,
    ];
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
