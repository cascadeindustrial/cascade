<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Model\Quote\Email\Container;

/**
 * Class QuoteFollowUpIdentity
 *
 * @package Cart2Quote\Quotation\Model\Quote\Email\Container
 */
class QuoteFollowUpIdentity extends AbstractQuoteIdentity
{
    use \Cart2Quote\Features\Traits\Model\Quote\Email\Container\QuoteFollowUpIdentity {
    }

    /**
     * Configuration paths
     */
    const XML_PATH_EMAIL_COPY_METHOD = 'cart2quote_email/quote_followup/copy_method';
    const XML_PATH_EMAIL_COPY_TO = 'cart2quote_email/quote_followup/copy_to';
    const XML_PATH_EMAIL_IDENTITY = 'cart2quote_email/quote_followup/identity';
    const XML_PATH_EMAIL_TEMPLATE = 'cart2quote_email/quote_followup/template';
    const XML_PATH_EMAIL_ENABLED = 'cart2quote_email/quote_followup/enabled';
}
