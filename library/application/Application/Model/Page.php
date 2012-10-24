<?php

namespace Application\Model;

class Page extends \Nerd\Model
{
    const DELIMITER = '@@';
    const PAGE_HOME = 'HOME';
    const PAGE_404  = '404';

    protected static $table = 'nerd_pages';

    public static function search($search, $order = '`title` ASC')
    {
        $search  = strtolower("{$search}%");
        $sql     = <<<SQL
SELECT *
  FROM `nerd_pages`
  WHERE
       LOWER(`title`)    LIKE ?
    OR LOWER(`subtitle`) LIKE ?
    OR LOWER(`uri`)      LIKE ?
  ORDER BY $order
SQL;

        return static::findAll($sql, $search, $search, $search);
    }

    /**
     * Delete method overload
     *
     * The Page model throws an exception when you attempt to delete a special URI.
     * These URI's are used within Nerd CMS to designate "special" pages such as
     * errors and the home page database entry.
     *
     * [!!] Overload this method at your own risk, deleting special URI's within the
     *      database table will break Nerd CMS
     *
     * @throws Nerd\DB\Exception On a special page deletion attempt.
     * @see    Nerd\Model::delete
     *
     * @return array Common result array
     */
    public function delete()
    {
        if (strpos($this->uri, '@@') !== false) {
            throw new \Nerd\DB\Exception('You are not permitted to delete special URIs.');
        }

        return parent::delete();
    }

    /**
     * Form builder overload
     *
     * @todo Incorporate site lookup into site id call
     *
     * @see Nerd\Model::form()
     */
    public function form(\Nerd\Form $form = null)
    {
        $form = parent::form($form);

        // Disable special uri's
        $uri  = $form->findByAttribute('id', 'page_uri');
        $this->isSpecialUri() and $uri->disabled(true);

        // Transform site_id into a hidden field.
        $site = $form->findByAttribute('id', 'page_site_id')
            ->type('hidden')
            ->wrap(false)
            ->wrapField(false)
            ->value(1);

            unset($site->label);

        // We need to transform the layout_id field to a select
        $layout = $form->findByAttribute('id', 'page_layout_id')->remove();

        // Steal options from current layout form object.
        $options = $layout->option('options');
        unset($options['type'], $options['maxlength'], $layout);
        $options['options'] = [
            'one' => 'One',
            'two' => 'Two',
        ];

        // Create a new select field with layout options.
        $form->field('select', $options, true)
            ->selected($this->layout_id ?: null)
            ->label('Layout', ['class' => 'control-label']);

        return $form;
    }

    public function isSpecialUri($uri = null)
    {
        if ($uri === null) {
            $uri = $this->uri;
        }

        return strpos($uri, '@@') !== false;
    }
}
