<?php

namespace Rougin\Wildfire\Traits;

/**
 * Paginate Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait PaginateTrait
{
    /**
     * Limits the data based on given configuration and generates pagination links.
     *
     * @param  integer $page
     * @param  array   $config
     * @return array
     */
    public function paginate($page, $config = array())
    {
        $this->load->library('pagination');

        $config = $this->prepare((array) $config);

        $offset = $this->offset($config, $page);

        $this->pagination->initialize($config);

        // TODO: Isolate this method from this trait
        $result = $this->limit($page, $offset)->all();

        $links = $this->pagination->create_links();

        return array((array) $result, $links);
    }

    /**
     * Returns the offset from the defined configuration.
     *
     * @param  array   $config
     * @param  integer $page
     * @return integer
     */
    protected function offset(array $config, $page)
    {
        $offset = $this->uri->segment($config['uri_segment']);

        if ($config['page_query_string'] === true) {
            $segment = $config['query_string_segment'];

            $offset = $this->input->get($segment);
        }

        if ($config['use_page_numbers'] && $offset != 0) {
            $offset = ($page * $offset) - (integer) $page;
        }

        return $offset;
    }

    /**
     * Retrieves configuration from pagination.php.
     * If not available, will based on given and default data.
     *
     * @param  array $config
     * @return array
     */
    protected function prepare(array $config)
    {
        $this->load->helper('url');

        $items = array('base_url' => current_url());

        $items['page_query_string'] = false;
        $items['query_string_segment'] = 'per_page';
        $items['uri_segment'] = 3;
        $items['use_page_numbers'] = false;

        foreach ($items as $key => $value) {
            $item = $this->config->item((string) $key);

            $item && $config[(string) $key] = $item;

            isset($config[$key]) || $config[$key] = $value;
        }

        return $config;
    }
}
