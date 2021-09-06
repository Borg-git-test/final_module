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
   *   1 => 1,
   *   2 => 3,
   * ];
   * Which means table with id 0 has one row and table 1 has 3 rows.
   */
  protected $rowId;

  protected $startRow = 1;

  public function buildForm(array $form, FormStateInterface $form_state) {

    $this->rowId = is_null($this->rowId) ? [
      $this->startRow => $this->startRow,
    ] : $this->rowId;

    $form['add_table'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Table'),
      '#submit' => ['::addTable'],
      '#ajax' => [
        'callback' => '::pleaseWork',
        'wrapper' => 'quarter',
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::pleaseWork',
        'wrapper' => 'quarter',
      ],
    ];
    $form['tables'] = [
      '#attributes' => ['id' => 'tables'],
      '#type' => 'container',
      '#tree' => TRUE,
    ];
    $form['#attributes'] = [
      'id' => 'quarter',
    ];
    $this->buildTables($form);

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
    $this->rowId[] = $this->startRow;
    $form_state->setRebuild();
  }

  public function buildTables(array &$form) {
    foreach ($this->rowId as $tableId => $rows) {
      $this->buildTable($form, $tableId);
    }
    return $form;
  }

  public function buildTable(array &$form, int $tableId) {

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
        ],
      ],

      'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#attributes' => ['id' => $tableId],
      ],
    ];

    for ($i = $this->rowId[$tableId]; $i >= $this->startRow; $i--) {
      $year = date('Y') - $i + $this->startRow;
      $form['tables'][$tableId]['table'][$i] = $this->buildRow($year);
    }

    return $form;
  }

  public function buildRow($year) {

    $quarters = [
      'q1' => [
        'jan',
        'feb',
        'mar',
      ],
      'q2' => [
        'apr',
        'may',
        'jun',
      ],
      'q3' => [
        'jul',
        'aug',
        'sep',
      ],
      'q4' => [
        'oct',
        'nov',
        'dec',
      ],
    ];

    $row['year'] = ['#markup' => $year];

    foreach ($quarters as $quarter => $month) {
      foreach ($month as $mon) {
        $row[$mon] = [
          '#type' => 'number',
          '#attributes' => [
            'style' => 'width: 8em;',
          ],
        ];
      }
      $row[$quarter] = ['#markup' => '<span class="quarter">' . 0 . '</span>'];
    }
    $row['ytd'] = ['#markup' => '<span class="summary">' . 0 . '</span>'];

    return $row;
  }

  public function validateTable(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues()['tables'];
    foreach ($values as $key => $table) {
      $mergeRows[$key] = [];

      // Record all rows in one numeric array.
      foreach ($table['table'] as $row) {
        $mergeRows[$key] = array_merge($mergeRows[$key], array_values($row));
      }

      // If field is empty return false.
      $field = 0;
      foreach ($mergeRows[$key] as $value) {
        if ($value === "") {
          $value = 0;
        }
        else {
          $value = 1;
        }
        $field += $value;
      }
      if ($field == 0) {
        return FALSE;
      }

      // If line break return false.
      $filterTable[$key] = array_filter($mergeRows[$key], function ($value) {
        return $value !== "";
      });
      if ((array_key_last($filterTable[$key]) - array_key_first($filterTable[$key])) != count($filterTable[$key]) - 1) {
        return FALSE;
      }

//      if (count($table['table']) == 1) {
//        $keyOneRow = $key;
//      }

//      if (array_key_last($filterTable[$keyOneRow]) !== array_key_last($filterTable[$key]) && array_key_first($filterTable[$keyOneRow]) !== array_key_first($filterTable[$key])) {
//        return FALSE;
//      }

    }

    return TRUE;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

    $valid = $this->validateTable($form, $form_state);
//    $values = $form_state->getValues()['tables'];

    if ($valid) {
      $this->messenger()->addStatus("Valid");
    }
    else {
      $this->messenger()->addError("Invalid");
    }

//    $this->messenger()->addMessage(print_r($values, TRUE));
//    $form_state->setRebuild();
    return $form;
  }

}
