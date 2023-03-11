<?php

namespace Drupal\fidesio_articles\Service;

class FidesioArticlesService {

  public function getNumberOfArticles($articlesNumber) {
    $lastArticles = [];
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'article_fidesio');
    $query->condition('status', 1);
    $query->sort('created', 'ASC');
    $query->range(0, $articlesNumber);
    $nids = $query->execute();

    if (!empty($nids)) {
      $lastArticles = \Drupal\node\Entity\Node::loadMultiple($nids);
    }

    return $lastArticles;
  }

}