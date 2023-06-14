<?php


namespace Cminds\Creditline\Model\Config\CreditTotalOrder;

use Magento\Config\Model\Config\CommentInterface;
use Magento\Sales\Model\Config as SalesConfig;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Comment implements CommentInterface
{
    public function __construct(
        SalesConfig $salesConfig
    ) {
        $this->salesConfig = $salesConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommentText($elementValue)
    {
        $totals = $this->salesConfig->getGroupTotals('quote', 'totals');

        $sort = [];
        foreach ($totals as $key => $total) {
            $order      = isset($total['sort_order']) ? $total['sort_order'] : 0;
            $sort[$key] = $order;
        }
        asort($sort);

        $html = '<div class="mst-creditline__config-totals"><table><tr><th>Total Name</th><th>Order</th></tr>';

        foreach ($sort as $key => $order) {
            $name = ucwords(str_replace('_', ' ', $key));
            $html .= '<tr><td class="' . $key . '">' . $name . '</td>
                <td class="' . $key . '">' . $order . '</td></tr>';
        }
        $html .= '</table></div>';

        return $html;
    }
}