<?php

namespace Rougin\Wildfire\Traits;

/**
 * @property \CI_Config     $config
 * @property \CI_Input      $input
 * @property \CI_Loader     $load
 * @property \CI_Pagination $pagination
 * @property \CI_URI        $uri
 *
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
trait PaginateTrait
{
    /**
     * Limits the data based on given configuration and generates pagination links.
     *
     * @param integer              $page
     * @param integer              $total
     * @param array<string, mixed> $config
     *
     * @return array<integer, integer|string>
     */
    public function paginate($page, $total, $config = array())
    {
        $this->load->library('pagination');

        $pagination = $this->pagination;

        $config = $this->prepare((array) $config);

        $config['per_page'] = (int) $page;

        $config['total_rows'] = (int) $total;

        $offset = $this->offset($page, $config);

        $pagination->initialize($config);

        $links = $pagination->create_links();

        return array($offset, $links);
    }

    /**
     * Returns the offset from the defined configuration.
     *
     * @param integer              $page
     * @param array<string, mixed> $config
     *
     * @return integer
     */
    protected function offset($page, $config)
    {
        $offset = $this->uri->segment($config['uri_segment']);

        if (array_key_exists('page_query_string', $config))
        {
            $segment = $config['query_string_segment'];

            $offset = $this->input->get((string) $segment);
        }

        $numbers = $config['use_page_numbers'] && $offset !== 0;

        return $numbers ? ($page * $offset) - $page : $offset;
    }

    /**
     * Returns the pagination configuration.
     *
     * @param array<string, mixed> $config
     *
     * @return array<string, string>
     */
    protected function prepare($config)
    {
        $this->load->helper('url');

        $items = array('base_url' => current_url());

        $items['page_query_string'] = false;
        $items['query_string_segment'] = 'per_page';
        $items['uri_segment'] = 3;
        $items['use_page_numbers'] = false;

        foreach ((array) $items as $key => $value)
        {
            if ($this->config->item($key))
            {
                $config[$key] = $this->config->item($key);

                continue;
            }

            if (! array_key_exists($key, $config))
            {
                $config[$key] = $value;
            }
        }

        return $config;
    }
}
