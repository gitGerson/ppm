<?php

declare(strict_types=1);

namespace BezhanSalleh\PluginEssentials\Concerns\Resource;

trait HasGlobalSearch
{
    use DelegatesToPlugin;

    public static function isGloballySearchable(): bool
    {
        $pluginResult = static::delegateToPlugin(
            'HasGlobalSearch',
            'isGloballySearchable'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('isGloballySearchable')
            : (bool) $pluginResult;
    }

    public static function canGloballySearch(): bool
    {
        $pluginResult = static::delegateToPlugin(
            'HasGlobalSearch',
            'canGloballySearch'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('canGloballySearch')
            : $pluginResult;
    }

    public static function getGlobalSearchResultsLimit(): int
    {
        $pluginResult = static::delegateToPlugin(
            'HasGlobalSearch',
            'getGlobalSearchResultsLimit'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('getGlobalSearchResultsLimit')
            : (int) $pluginResult;
    }

    public static function isGlobalSearchForcedCaseInsensitive(): ?bool
    {
        $pluginResult = static::delegateToPlugin(
            'HasGlobalSearch',
            'isGlobalSearchForcedCaseInsensitive'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('isGlobalSearchForcedCaseInsensitive')
            : $pluginResult;
    }

    public static function shouldSplitGlobalSearchTerms(): bool
    {
        $pluginResult = static::delegateToPlugin(
            'HasGlobalSearch',
            'shouldSplitGlobalSearchTerms'
        );

        return static::isNoPluginResult($pluginResult)
            ? static::getParentResult('shouldSplitGlobalSearchTerms')
            : $pluginResult;
    }
}
