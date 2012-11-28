<?php

/**
 * Nerd-CMS Model namespace
 *
 * @package Nerd-CMS
 * @subpackage Models
 */
namespace Application\Model;

/**
 * Nerd-CMS Page Model
 *
 * Handles data pertaining to Nerd-CMS pages. The data access layer for pages
 * is fairly complex, and we do our best to keep this complexity to a minimum,
 * but it's difficult.
 *
 * @package Nerd-CMS
 * @subpackage Models
 */
class Page extends \Nerd\Model
{
    const DELIMITER = '@@';
    const PAGE_HOME = 'HOME';
    const PAGE_404  = '404';

    protected static $table = 'nerd_pages';
    protected static $columns;
    protected static $constraints;
    protected static $columnNames;
    protected static $primary;

    /**
     * Search pages
     *
     * @param    string          Search term
     * @param    string          Sort results by...
     * @return   this
     */
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
        if ($this->isSpecialUri()) {
            throw new \Nerd\DB\Exception('You are not permitted to delete special URIs.');
        }

        return parent::delete();
    }

    /**
     * Form builder overload
     *
     * Automatic form creation does not completely compensate for the what is
     * required to display the pages form. We need to transform some of the
     * form elements, set default values, among many other things.
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
            ->wrapField(false);

            unset($site->label);

        // We need to transform the layout_id field to a select
        $layout = $form->findByAttribute('id', 'page_layout_id')->remove();

        // Steal attributes from current layout form object.
        $options = $layout->attributes();
        unset($options['type'], $options['maxlength'], $options['value'], $layout);
        $options['options'] = \Theme\Theme::instance()->layouts;

        // Create a new select field with layout options.
        $form->field('select', $options, true)
            ->selected($this->layout_id ?: null)
            ->label('Layout');

        return $form;
    }

    public function isSpecialUri()
    {
        return strpos($this->uri, static::DELIMITER) !== false;
    }
}
