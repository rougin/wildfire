<?php

namespace Rougin\Wildfire\Traits;

/**
 * Paginate Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property \CI_URI        $uri
 * @property \CI_Loader     $load
 * @property \CI_Config     $config
 * @property \CI_Pagination $pagination
 * @property \CI_Input      $input
 *
 * @method integer countAll()
 * @method self    limit($value, $offset = '')
 */
trait PaginateTrait
{
    /**
     * Limits the data based on given configuration and generates pagination links.
     *
     * @param  integer $perPage
     * @param  array   $config
     * @return array
     */
    public function paginate($perPage, $config = [])
    {
        $this->load->library('pagination');

        $this->prepareConfiguration($config);

        $config['per_page']   = $perPage;
        $config['total_rows'] = $this->countAll();

        $offset = $this->getOffset($config);

        $this->pagination->initialize($config);

        $items = $this->limit($perPage, $offset)->all();

        return [ $items, $this->pagination->create_links() ];
    }

    /**
     * Returns the offset from the defined configuration.
     *
     * @param  array $config
     * @return integer
     */
    protected function getOffset(array $config)
    {
        $offset = $this->uri->segment($config['uri_segment']);

        if ($config['page_query_string']) {
            $offset = $this->input->get($config['query_string_segment']);
        }

        if ($config['use_page_numbers'] && $offset != 0) {
            $offset = $config['per_page'] * $offset - $config['per_page'];
        }

        return $offset;
    }

    /**
     * Retrieves configuration from pagination.php.
     * If not available, will based on given and default data.
     *
     * @param  array $config
     * @return void
     */
    protected function prepareConfiguration(array &$config)
    {
        $this->load->helper('url');

        $items = [
            'base_url'             => current_url(),
            'page_query_string'    => false,
            'query_string_segment' => 'per_page',
            'uri_segment'          => 3,
            'use_page_numbers'     => false,
        ];

        foreach ($items as $item => $defaultValue) {
            if ($this->config->item($item) !== null) {
                $config[$item] = $this->config->item($item);
            } elseif (! isset($config[$item])) {
                $config[$item] = $defaultValue;
            }
        }
    }
}
