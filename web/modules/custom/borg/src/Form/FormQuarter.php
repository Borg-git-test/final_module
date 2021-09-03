<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class FormQuarter extends FormBase {

  public function getFormId() {
    return 'quarter';
  }

  /**
   * @var string[]
   *  Array of form tables by id and tables rows.
   *
   *  Eg:
   * $this->tableRows = [
   *   0 => 1,
   *   1 => 3,
   * ];
   * Which means table with id 0 has one row and table 1 has 3 rows.
   */
  protected $rowId;

  protected $r = 1;

  public function buildForm(array $form, FormStateInterface $form_state) {

    $this->rowId = is_null($this->rowId) ? [
      $this->r => $this->r,
    ] : $this->rowId;

    $form['add_table'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Table'),
      '#submit' => ['::addTable'],
      '#ajax' => [
        'callback' => '::pleaseWork',
        'wrapper' => 'quarter',
//        'callback' => '::buildTables',
//        'wrapper' => 'tables',
      ],
    ];

//    $form['add_row'] = [
//      '#type' => 'submit',
//      '#value' => $this->t('Add Year'),
//      '#submit' => ['::addRows'],
//      '#ajax' => [
////        'callback' => '::pleaseWork',
////        'wrapper' => 'quarter',
//        'callback' => '::buildTable',
//        'wrapper' => 'table',
//      ],
//    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
//      '#submit' => ['::add'],
    ];
//    $form['tables'] = [
//      '#type' => 'container',
//    ];

    $form['form'] = [
//      '#type' => 'container',
//      '#attributes' => ['id' => 'container'],
      $this->buildTables($form),
    ];
    $form['#attributes'] = [
      'id' => 'quarter',
    ];

    return $form;
  }

  function pleaseWork($pleaseWork) {
    return $pleaseWork;
  }

  function addRows(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getTriggeringElement()['#name'];
    ++$this->rowId[$id];
    $form_state->setRebuild();
  }

  function addTable(array &$form, FormStateInterface $form_state) {
    $this->rowId[] = $this->r;
    $form_state->setRebuild();
  }

  public function buildTables(array $form) {
    $form['tables'] = [
      '#attributes' => ['id' => 'tables'],
      '#type' => 'container',
    ];
    foreach ($this->rowId as $tableId => $rows) {
      $form['tables'][$tableId] = $this->buildTable($form, $tableId);
    }
//    for ($i = -1; $i <= $this->table_id; $i++) {
//      $form['tables'][$i] = $this->buildTable($form, $i);
//    }
    return $form['tables'];
  }

  public function buildTable(array $form, int $tableId) {

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

    $form['tables'][$tableId] = [
      'add_row' => [
        '#type' => 'submit',
        '#name' => $tableId,
        '#value' => $this->t('Add Year'),
        '#submit' => ['::addRows'],
        '#ajax' => [
          'callback' => '::pleaseWork',
          'wrapper' => 'quarter',
//          'callback' => '::buildTables',
//          'wrapper' => $tableId,
        ],
      ],

      'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#attributes' => ['id' => $tableId],
      ],
    ];

    for ($i = $this->rowId[$tableId]; $i > 0; $i--) {
      $year = date('Y') - $i + $this->r;
      $form['tables'][$tableId]['table'][$tableId . '-' . $i] = $this->buildRow($year, 0, 0, 0, 0, 0);
    }

    return $form['tables'][$tableId];
  }

  public function buildRow($year, $q1, $q2, $q3, $q4, $ytd) {

    return [
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

      $values = $form_state->getValues();
      $this->messenger()->addMessage(print_r($values,true));
  }

}
