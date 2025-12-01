# Kairos — Package Specification

> **Cluster:** `core`
> **Language:** `php`
> **Milestone:** `m2`
> **Repo:** `https://github.com/decodelabs/kairos`
> **Role:** Time and date utilities

## Overview

### Purpose

Kairos provides time and date utilities for PHP applications. It extends PHP's native date/time capabilities with:

- High-precision time measurement using `MicroTime`
- Elapsed time tracking with `Timer`
- PSR-20 clock interface implementation with multiple clock types
- Time of day representation (`TimeOfDay`)
- Enhanced timezone handling (`TimeZone`)
- Lucid integration for date/time validation and sanitization

Kairos is designed to provide reliable, precise time handling for applications that need accurate time measurement, timezone management, and date/time validation.

### Non-Goals

- Kairos does not provide calendar or scheduling functionality
- It does not handle date arithmetic beyond basic time operations
- It does not provide timezone database management
- It does not implement date formatting or localization (uses Carbon for this)
- It does not provide recurring event or cron expression parsing
- It does not handle business day calculations or holidays

## Role in the Ecosystem

### Cluster & Positioning

Kairos belongs to the **core** cluster, providing fundamental time and date utilities. It sits alongside other core packages like Coercion (type casting), Exceptional (exceptions), and Nuance (type inspection).

### Usage Contexts

Kairos is used for:

- Performance measurement and profiling
- Time-based calculations and comparisons
- Timezone-aware date/time operations
- Date/time validation in forms and APIs
- Time of day filtering and scheduling
- Applications requiring high-precision time measurement
- Testing with fixed or controlled time

## Public Surface

### Key Types

- **`Kairos\Timer`** — Class for measuring elapsed time. Tracks start and end times using `MicroTime` and provides formatted output.

- **`Kairos\MicroTime`** — Class representing high-precision time with seconds and nanoseconds. Provides arithmetic operations and conversion to various time units.

- **`Kairos\Clock`** — Interface extending PSR-20 `ClockInterface`. Adds timezone support and sleep functionality.

- **`Kairos\Clock\System`** — Clock implementation using system time via Carbon.

- **`Kairos\Clock\Monotonic`** — Clock implementation using monotonic time (hrtime) for consistent time progression.

- **`Kairos\Clock\Fixed`** — Clock implementation that returns a fixed time, useful for testing.

- **`Kairos\ClockTrait`** — Trait providing common clock functionality including timezone management and sleep.

- **`Kairos\TimeOfDay`** — Class representing a time of day (hours, minutes, seconds) with normalization and string parsing.

- **`Kairos\TimeZone`** — Class extending `DateTimeZone` with enhanced timezone handling, location information, and canonical ID resolution.

- **`Kairos\TimeZoneLocation`** — Data class representing timezone location information (country code, latitude, longitude, comments).

- **`Kairos\TimeZoneType`** — Enum defining timezone types: `Offset`, `Abbreviation`, `Region`.

- **`Lucid\Processor\Date`** — Lucid processor for coercing values to `Carbon` instances.

- **`Lucid\Processor\Interval`** — Lucid processor for coercing values to `CarbonInterval` instances.

- **`Lucid\Processor\TimeOfDay`** — Lucid processor for coercing values to `TimeOfDay` instances.

- **`Lucid\Constraint\DateTime\Min`** — Lucid constraint for validating minimum date/time values.

- **`Lucid\Constraint\DateTime\Max`** — Lucid constraint for validating maximum date/time values.

- **`Lucid\Constraint\DateTime\Range`** — Lucid constraint for validating date/time ranges.

- **`Lucid\Constraint\Interval\Min`** — Lucid constraint for validating minimum interval values.

- **`Lucid\Constraint\Interval\Max`** — Lucid constraint for validating maximum interval values.

- **`Lucid\Constraint\Interval\Range`** — Lucid constraint for validating interval ranges.

### Main Entry Points

- **`Timer::__construct(int|float|string|array|MicroTime|null $start)`** — Creates a timer. Defaults to current time if not specified.

- **`Timer::stop(int|float|string|array|MicroTime|null $end)`** — Stops the timer at the given time (or current time).

- **`Timer::time`** — Property that returns elapsed time as `MicroTime`.

- **`MicroTime::hrtime()`** — Creates a `MicroTime` instance from `hrtime()`.

- **`MicroTime::microtime()`** — Creates a `MicroTime` instance from `microtime(true)`.

- **`MicroTime::from(int|float|string|array|MicroTime|null $seconds)`** — Creates a `MicroTime` instance from various input formats.

- **`MicroTime::add(int|float|string|array|MicroTime $seconds)`** — Adds time to the instance.

- **`MicroTime::subtract(int|float|string|array|MicroTime $seconds)`** — Subtracts time from the instance.

- **`Clock::now()`** — Returns current time as `CarbonImmutable`.

- **`Clock::sleep(int|float|array|MicroTime $seconds)`** — Sleeps for the specified duration.

- **`Clock::withTimeZone(string|DateTimeZone|null $timezone)`** — Returns a new clock instance with the specified timezone.

- **`TimeOfDay::from(int|string|TimeOfDay|DateTimeInterface|null $value)`** — Creates a `TimeOfDay` instance from various inputs.

- **`TimeOfDay::fromString(string $string)`** — Parses a time string (e.g., "14:30:00") into a `TimeOfDay` instance.

- **`TimeZone::from(string|DateTimeZone|TimeZone|null $timeZone)`** — Creates a `TimeZone` instance with canonical ID resolution.

- **`TimeZone::getDefault()`** — Gets the default PHP timezone.

- **`TimeZone::setDefault(string|TimeZone $timeZone)`** — Sets the default PHP timezone.

- **`TimeZone::getActive()`** — Gets the active timezone (thread-local).

- **`TimeZone::setActive(string|TimeZone $timeZone)`** — Sets the active timezone (thread-local).

## Dependencies

### Decode Labs

- **`coercion`** — Used for type coercion when parsing time values and converting between formats.

- **`exceptional`** — Used for exception handling throughout the package.

- **`nuance`** — Used for type inspection and debugging via `Dumpable` interface.

### External

- **`psr/clock`** — PSR-20 clock interface for standardized time access.

- **`nesbot/carbon`** — Used for date/time manipulation and formatting. Provides `Carbon`, `CarbonImmutable`, and `CarbonInterval` classes.

## Behaviour & Contracts

### Invariants

- `MicroTime` maintains seconds and nanoseconds separately for precision
- Timer start time is immutable once set
- Timer end time can be set once via `stop()`
- Clock implementations return `CarbonImmutable` instances
- `TimeOfDay` values are normalized to valid ranges (0-23 hours, 0-59 minutes, 0-59 seconds)
- `TimeZone` instances use canonical IDs when possible
- Lucid processors and constraints work with Carbon types

### Input & Output Contracts

- **`Timer::__construct(int|float|string|array|MicroTime|null $start): Timer`** — Creates a timer. Accepts various time formats or uses current time. Returns a running timer.

- **`Timer::stop(int|float|string|array|MicroTime|null $end): static`** — Stops the timer. Returns self for method chaining.

- **`Timer::time: MicroTime`** — Returns elapsed time. Calculates from current time if timer is still running.

- **`MicroTime::from(int|float|string|array|MicroTime|null $seconds): MicroTime`** — Creates a `MicroTime` instance. Accepts seconds as int/float, hrtime array, string, or existing `MicroTime`. Throws `InvalidArgument` for invalid formats.

- **`MicroTime::add(int|float|string|array|MicroTime $seconds): MicroTime`** — Returns a new `MicroTime` instance with time added. Handles nanosecond overflow.

- **`MicroTime::subtract(int|float|string|array|MicroTime $seconds): MicroTime`** — Returns a new `MicroTime` instance with time subtracted. Handles nanosecond underflow.

- **`MicroTime::toFloat(): float`** — Returns time as a float in seconds.

- **`MicroTime::toArray(): array{int,int}`** — Returns time as `[seconds, nanoseconds]` array.

- **`MicroTime::asSeconds(): int`** — Returns seconds component.

- **`MicroTime::asMilliseconds(): int`** — Returns total milliseconds.

- **`MicroTime::asMicroseconds(): int`** — Returns total microseconds.

- **`MicroTime::asNanoseconds(): int`** — Returns total nanoseconds.

- **`Clock::now(): CarbonImmutable`** — Returns current time in the clock's timezone.

- **`Clock::sleep(int|float|array|MicroTime $seconds): void`** — Sleeps for the specified duration. Uses `usleep()` for sub-second precision.

- **`TimeOfDay::from(int|string|TimeOfDay|DateTimeInterface|null $value): TimeOfDay`** — Creates a `TimeOfDay` instance. Throws `InvalidArgument` if value cannot be parsed.

- **`TimeOfDay::tryFrom(int|string|TimeOfDay|DateTimeInterface|null $value): ?TimeOfDay`** — Attempts to create a `TimeOfDay` instance. Returns null on failure.

- **`TimeOfDay::fromString(string $string): TimeOfDay`** — Parses a time string (HH:MM:SS format). Throws `InvalidArgument` if parsing fails.

- **`TimeZone::from(string|DateTimeZone|TimeZone|null $timeZone): TimeZone`** — Creates a `TimeZone` instance with canonical ID resolution. Normalizes abbreviations and region names.

- **`TimeZone::getDefault(): TimeZone`** — Returns the default PHP timezone.

- **`TimeZone::setDefault(string|TimeZone $timeZone): void`** — Sets the default PHP timezone globally.

- **`TimeZone::getActive(): TimeZone`** — Returns the active thread-local timezone.

- **`TimeZone::setActive(string|TimeZone $timeZone): void`** — Sets the active thread-local timezone.

## Error Handling

Kairos uses the Exceptional pattern for error handling. Key exception types:

- **`ComponentUnavailable`** — Thrown when `hrtime()` is unavailable or fails.

- **`InvalidArgument`** — Thrown when time values cannot be parsed or are invalid.

- **`UnexpectedValue`** — Thrown when Lucid processors cannot coerce values to expected types.

Exceptions preserve the original service context and include detailed error messages.

## Configuration & Extensibility

### Extension Points

- **Custom Clock Implementations** — Implement `Clock` interface to provide custom time sources (e.g., NTP-synced, simulated time).

- **Custom Lucid Processors** — Implement `Processor` interface to add custom date/time coercion logic.

- **Custom Lucid Constraints** — Implement `Constraint` interface to add custom date/time validation rules.

### Configuration

- **Default Timezone** — Managed via PHP's `date_default_timezone_set()` or `TimeZone::setDefault()`.

- **Active Timezone** — Thread-local timezone managed via `TimeZone::setActive()` for per-request timezone handling.

- **Clock Timezone** — Each clock instance can have its own timezone, set via constructor or `withTimeZone()`.

## Interactions with Other Packages

- **Carbon** — Used for date/time manipulation and formatting. Provides the primary date/time objects returned by clocks and processors.

- **Coercion** — Used for type conversion when parsing time values.

- **Nuance** — Used for type inspection via `Dumpable` interface on `Timer`, `TimeOfDay`, and `TimeZone`.

- **Lucid** — Integrated via processors and constraints for date/time validation and sanitization in forms and APIs.

## Usage Examples

### Timer Usage

```php
use DecodeLabs\Kairos\Timer;

// Start timer
$timer = new Timer();

// Do work
sleep(1);

// Get elapsed time
$elapsed = $timer->time; // MicroTime instance
echo $elapsed; // "1.000 s"

// Stop timer
$timer->stop();
$total = $timer->time;
```

### MicroTime Operations

```php
use DecodeLabs\Kairos\MicroTime;

// Create from hrtime
$time = MicroTime::hrtime();

// Create from seconds
$time = MicroTime::from(1.5);

// Create from array
$time = MicroTime::from([1, 500000000]); // 1 second, 500ms

// Arithmetic
$time1 = MicroTime::from(1.5);
$time2 = MicroTime::from(0.3);
$sum = $time1->add($time2);
$diff = $time1->subtract($time2);

// Conversions
$seconds = $time->asSeconds();
$milliseconds = $time->asMilliseconds();
$microseconds = $time->asMicroseconds();
$nanoseconds = $time->asNanoseconds();
$float = $time->toFloat();
```

### Clock Usage

```php
use DecodeLabs\Kairos\Clock\System;
use DecodeLabs\Kairos\Clock\Monotonic;
use DecodeLabs\Kairos\Clock\Fixed;

// System clock
$clock = new System('UTC');
$now = $clock->now(); // CarbonImmutable

// Monotonic clock (for consistent time progression)
$clock = new Monotonic('UTC');
$now = $clock->now();

// Fixed clock (for testing)
$clock = new Fixed('2024-01-01 12:00:00', 'UTC');
$now = $clock->now(); // Always returns fixed time

// Sleep
$clock->sleep(MicroTime::from(0.5)); // Sleep 500ms

// Change timezone
$clock = $clock->withTimeZone('America/New_York');
```

### TimeOfDay Usage

```php
use DecodeLabs\Kairos\TimeOfDay;

// Create from components
$time = new TimeOfDay(14, 30, 0); // 14:30:00

// Parse from string
$time = TimeOfDay::fromString('14:30:00');
$time = TimeOfDay::fromString('14:30'); // Seconds default to 0

// Create from DateTime
$dateTime = new DateTime('2024-01-01 14:30:00');
$time = TimeOfDay::fromDateTime($dateTime);

// Normalization
$time = new TimeOfDay(25, 70, 90); // Automatically normalized to 02:11:30

// String output
echo $time; // "14:30:00"
```

### TimeZone Usage

```php
use DecodeLabs\Kairos\TimeZone;

// Create from string
$tz = TimeZone::from('UTC');
$tz = TimeZone::from('America/New_York');

// Canonical ID resolution
$tz = TimeZone::from('est'); // Resolved to 'America/New_York'

// Get default
$default = TimeZone::getDefault();

// Set default
TimeZone::setDefault('UTC');

// Active timezone (thread-local)
TimeZone::setActive('America/New_York');
$active = TimeZone::getActive();

// Location information
$location = $tz->location; // TimeZoneLocation or null
if ($location) {
    echo $location->countryCode; // "US"
    echo $location->latitude; // 40.7128
    echo $location->longitude; // -74.0060
}
```

### Lucid Integration

```php
use DecodeLabs\Lucid\Processor\Date;
use DecodeLabs\Lucid\Constraint\DateTime\Min;
use DecodeLabs\Lucid\Constraint\DateTime\Max;

// Process date values
$processor = new Date();
$date = $processor->coerce('2024-01-01'); // Carbon instance

// Validate date range
$constraint = new Min(new DateTime('2024-01-01'));
$constraint->validate($date); // Validates minimum date

$constraint = new Max(new DateTime('2024-12-31'));
$constraint->validate($date); // Validates maximum date
```

## Implementation Notes (for Contributors)

### Architecture

- **High-Precision Time** — `MicroTime` maintains seconds and nanoseconds separately to avoid floating-point precision issues. Arithmetic operations handle overflow/underflow correctly.

- **Timer Implementation** — Timer uses `hrtime()` for high-precision measurement. End time is optional, allowing continuous measurement until stopped.

- **Clock Types** — Three clock implementations provide different time sources: System (real time), Monotonic (consistent progression), and Fixed (testing).

- **TimeOfDay Normalization** — Time components are automatically normalized when set outside valid ranges, allowing flexible input (e.g., 25 hours becomes 1 hour next day).

- **Timezone Canonicalization** — `TimeZone` uses `IntlTimeZone::getCanonicalID()` to resolve abbreviations and normalize timezone identifiers.

- **Lucid Integration** — Processors and constraints integrate with Lucid's validation system, providing type-safe date/time handling in forms and APIs.

- **Carbon Integration** — All date/time objects use Carbon for manipulation and formatting, providing a consistent API across the package.

### Performance Considerations

- `MicroTime` uses integer arithmetic for precision, avoiding floating-point errors
- Timer uses `hrtime()` which is more precise than `microtime()`
- Timezone canonicalization is cached by PHP's Intl extension
- Clock implementations are lightweight wrappers around Carbon

### Design Decisions

- **MicroTime Precision** — Using separate seconds and nanoseconds provides nanosecond precision without floating-point issues.

- **PSR-20 Compliance** — Implementing PSR-20 clock interface provides interoperability with other packages.

- **Carbon Dependency** — Using Carbon provides rich date/time manipulation while maintaining compatibility with PHP's DateTime.

- **TimeOfDay Normalization** — Automatic normalization allows flexible input while maintaining valid time values.

- **Thread-Local Timezone** — Active timezone provides per-request timezone handling without global state changes.

- **Lucid Integration** — Providing processors and constraints enables type-safe date/time validation throughout the ecosystem.

## Testing & Quality

**Code Quality:** 3/5 — Functional codebase with good structure. Some areas may benefit from additional features or refinements.

**README Quality:** 3/5 — Good documentation with clear usage examples covering main use cases.

**Documentation:** 0/5 — No formal documentation beyond README.

**Tests:** 0/5 — No test suite currently.

See `composer.json` for supported PHP versions.

## Roadmap & Future Ideas

- Enhanced documentation and API reference
- Test suite implementation
- Additional time utilities (durations, periods)
- Calendar and scheduling functionality
- Timezone database utilities
- Date arithmetic enhancements
- Performance optimizations
- Additional clock implementations

## References

- [PSR-20: Clock](https://www.php-fig.org/psr/psr-20/)
- [Carbon Documentation](https://carbon.nesbot.com/docs/)
- [Decode Labs Chorus](https://github.com/decodelabs/chorus)
- [Kairos Repository](https://github.com/decodelabs/kairos)

