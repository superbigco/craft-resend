<?php

namespace superbig\resend;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\helpers\MailerHelper;
use yii\base\Event;

/**
 * Resend plugin
 *
 * @method static Resend getInstance()
 * @author Superbig <support@superbig.co>
 * @copyright Superbig
 * @license https://craftcms.github.io/license/ Craft License
 */
class Resend extends Plugin
{
    public string $schemaVersion = '1.0.0';

    public function init(): void
    {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
            // ...
        });
    }

    private function attachEventHandlers(): void
    {
        $eventName = defined(sprintf('%s::EVENT_REGISTER_MAILER_TRANSPORT_TYPES', MailerHelper::class))
            ? MailerHelper::EVENT_REGISTER_MAILER_TRANSPORT_TYPES // Craft 4
            /** @phpstan-ignore-next-line */
            : MailerHelper::EVENT_REGISTER_MAILER_TRANSPORTS; // Craft 5+

        Event::on(
            MailerHelper::class,
            $eventName,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = Adapter::class;
            }
        );
    }
}
