<?php
namespace superbig\resend;

use craft\behaviors\EnvAttributeParserBehavior;
use craft\helpers\App;
use craft\mail\transportadapters\BaseTransportAdapter;
use Symfony\Component\Mailer\Bridge\Resend\Transport\ResendApiTransport;

class Adapter extends BaseTransportAdapter
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Resend';
    }

    /**
     * @var string
     */
    public ?string $apiKey = null;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'apiKey' => \Craft::t('resend', 'API Key'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['parser'] = [
            'class' => EnvAttributeParserBehavior::class,
            'attributes' => [
                'apiKey',
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        return [
            [['apiKey'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        return \Craft::$app->getView()->renderTemplate('resend/settings', [
            'adapter' => $this,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function defineTransport(): array|\Symfony\Component\Mailer\Transport\AbstractTransport
    {
        $transport = new ResendApiTransport(App::parseEnv($this->apiKey));

        return $transport;
    }
}