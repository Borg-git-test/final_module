<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class FormQuarter extends FormBase {

  public function getFormId() {
    return 'quarter';
  }

  public $row_id;
  public $table_id = 0;

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['add_table'] = [
      '#type' => 'button',
      '#value' => $this->t('Add Table'),
      '#submit' => ['::adder'],
      '#ajax' => [
        'callback' => '::pleaseWork',
        'wrapper' => 'quarter',
      ],
    ];

    $form['add_row'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Year'),
      '#submit' => ['::add'],
      '#ajax' => [
//        'callback' => '::pleaseWork',
//        'wrapper' => 'quarter',
        'callback' => '::buildTable',
        'wrapper' => 'table',
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
//      '#submit' => ['::add'],
    ];

    $form['form'] = [
      $this->buildTable($form),
    ];
    $form['#attributes'] = [
      'id' => 'quarter',
    ];
//    $this->row_id++;
//    $form_state->set('tableRows', $this->row_id);
    return $form;
  }

  function pleaseWork($please_work) {
    return $please_work;
  }

  function add(FormStateInterface $form_state) {
//    $this->row_id = $form_state->get('tableRows');
    ++$this->row_id;
//    $form_state->set('tableRows', $this->row_id);
  }

//  function adder() {
//    return ++$this->table_id;
//  }

  public function buildTables(array $form) {
    $form['tables'] = ['#attributes' => ['id' => 'tables']];
    for ($i = 0; $i <= $this->table_id; $i++) {
      $form['tables'][$i] = $this->buildTable($form);
    }
//    $this->table_id++;
    return $form['tables'];
  }

  public function buildTable(array $form) {

//    $q1 = sprintf('%0.2g', (($form_state->get('jan') + $form_state->get('feb') + $form_state->get('mar') + 1) / 3));
//    $q2 = sprintf('%0.2g', (($form_state->get('apr') + $form_state->get('may') + $form_state->get('jun') + 1) / 3));
//    $q3 = sprintf('%0.2g', (($form_state->get('jul') + $form_state->get('aug') + $form_state->get('sep') + 1) / 3));
//    $q4 = sprintf('%0.2g', (($form_state->get('oct') + $form_state->get('nov') + $form_state->get('dec') + 1) / 3));
//    $ytd = sprintf('%0.2g', (($q1 + $q2 + $q3 + $q4 + 1) / 4));

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

    $form['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#attributes' => ['id' => 'table'],
    ];

    for ($i = $this->row_id; $i >= 0; $i--) {
      $year = date('Y') - $i;
      $form['table'][$i] = $this->buildRow($year, 0, 0, 0, 0, 0);
    }
//    ++$this->row_id;
//    $this->add();

    return $form['table'];
  }

  public function buildRow($year, $q1, $q2, $q3, $q4, $ytd) {

    $value = [
      'year' => ['#markup' => $year],
      'jan' => ['#type' => 'number'],
      'feb' => ['#type' => 'number'],
      'mar' => ['#type' => 'number'],
      'q1' => ['#markup' => $q1],
      'apr' => ['#type' => 'number'],
      'may' => ['#type' => 'number'],
      'jun' => ['#type' => 'number'],
      'q2' => ['#markup' => $q2],
      'jul' => ['#type' => 'number'],
      'aug' => ['#type' => 'number'],
      'sep' => ['#type' => 'number'],
      'q3' => ['#markup' => $q3],
      'oct' => ['#type' => 'number'],
      'nov' => ['#type' => 'number'],
      'dec' => ['#type' => 'number'],
      'q4' => ['#markup' => $q4],
      'ytd' => ['#markup' => $ytd],
    ];
    return $value;
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
