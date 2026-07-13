<?php

declare(strict_types=1);

namespace BezhanSalleh\PluginEssentials\Concerns\Resource;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

trait HasNavigation
{
    use DelegatesToPlugin;

    public static function getNavigationLabel(): string
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getNavigationLabel'
        );

        return (! static::isNoPluginResult($pluginResult) && filled($pluginResult))
            ? $pluginResult
            : static::getParentResult('getNavigationLabel');
    }

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getNavigationIcon'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getNavigationIcon')
            : $pluginResult;
    }

    public static function getActiveNavigationIcon(): BackedEnum | Htmlable | null | string
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getActiveNavigationIcon'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getActiveNavigationIcon')
            : $pluginResult;
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getNavigationGroup'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getNavigationGroup')
            : $pluginResult;
    }

    public static function getNavigationSort(): ?int
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getNavigationSort'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getNavigationSort')
            : $pluginResult;
    }

    public static function getNavigationBadge(): ?string
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getNavigationBadge'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getNavigationBadge')
            : $pluginResult;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getNavigationBadgeColor'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getNavigationBadgeColor')
            : $pluginResult;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getNavigationBadgeTooltip'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getNavigationBadgeTooltip')
            : $pluginResult;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'shouldRegisterNavigation'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('shouldRegisterNavigation')
            : $pluginResult;
    }

    public static function getNavigationParentItem(): ?string
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getNavigationParentItem'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getNavigationParentItem')
            : $pluginResult;
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        $pluginResult = static::delegateToPlugin(
            'HasNavigation',
            'getSubNavigationPosition'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getSubNavigationPosition')
            : $pluginResult;
    }
}
