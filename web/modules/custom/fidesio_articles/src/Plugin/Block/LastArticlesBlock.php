<?php

namespace Drupal\fidesio_articles\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "last_articles_block",
 *   admin_label = @Translation("Last articles"),
 * )
 */
class LastArticlesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['articles_number'] = [
      '#type' => 'number',
      '#title' => $this->t('Articles number'),
      '#description' => $this->t('Number of articles to display in the block '),
      '#default_value' => isset($config['articles_number']) ? $config['articles_number'] : 1,
      '#required' => TRUE,
    ];

    $form['cache_invalidation'] = [
      '#type' => 'number',
      '#title' => $this->t('Invalidation cache'),
      '#description' => $this->t('Duration of cache before unvalidation'),
      '#default_value' => isset($config['cache_invalidation']) ? $config['cache_invalidation'] : 0,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['articles_number'] = $values['articles_number'];
    $this->configuration['cache_invalidation'] = $values['cache_invalidation'];
  }

  public function build() {
    $config = $this->getConfiguration();
    $articlesNumber = $config['articles_number'];

    return [
      '#theme' => 'block_last_articles',
      '#articles' => \Drupal::service('fidesio_articles_service')
        ->getNumberOfArticles($articlesNumber),
    ];
  }

  public function getCacheTagsToInvalidate() {
  }

  public function getCacheMaxAge() {
    $config = $this->getConfiguration();

    return $config['cache_invalidation'] * 3600;
  }

}