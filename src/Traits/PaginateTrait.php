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
     * @param integer              $limit
     * @param integer              $total
     * @param array<string, mixed> $config
     *
     * @return array<integer, integer|string>
     */
    public function paginate($limit, $total, $config = array())
    {
        $this->load->library('pagination');

        $pagination = $this->pagination;

        $config = $this->prepare($config);

        $config['per_page'] = (int) $limit;

        $config['total_rows'] = $total;

        $offset = $this->offset($config);

        $pagination->initialize($config);

        $links = $pagination->create_links();

        return array($offset, $links);
    }

    /**
     * Returns the offset from the defined configuration.
     *
     * @param array<string, mixed> $config
     *
     * @return integer
     */
    protected function offset($config)
    {
        /** @var integer */
        $segment = $config['uri_segment'];

        /** @var integer */
        $limit = $config['per_page'];

        /** @var integer */
        $offset = $this->uri->segment($segment);

        if (array_key_exists('page_query_string', $config))
        {
            /** @var string */
            $segment = $config['query_string_segment'];

            /** @var integer */
            $offset = $this->input->get($segment);
        }

        $hasOffset = $offset !== 0 && $offset !== null;

        /** @var boolean */
        $useNumbers = $config['use_page_numbers'];

        $numbers = $useNumbers && $hasOffset;

        return $numbers ? ($limit * $offset) - $limit : $offset;
    }

    /**
     * Returns the pagination configuration.
     *
     * @param array<string, mixed> $config
     *
     * @return array<string, mixed>
     */
    protected function prepare($config)
    {
        $this->load->helper('url');

        $items = array('uri_segment' => 3);

        $items['page_query_string'] = false;
        $items['query_string_segment'] = 'per_page';
        $items['use_page_numbers'] = false;

        return array_merge($items, $config);
    }
}
