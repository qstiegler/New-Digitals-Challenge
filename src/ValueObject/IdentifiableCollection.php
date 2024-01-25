<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\ValueObject\Exception\CollectionDoesNotIncludeAll;
use App\ValueObject\Exception\ItemAlreadyInCollection;
use App\ValueObject\Exception\ItemDoesNotExist;
use App\ValueObject\Exception\ItemNotInCollection;
use DigitalCraftsman\Ids\ValueObject\IdList;

/**
 * @template T implements Identifiable
 *
 * @template-implements \IteratorAggregate<int, T>
 */
abstract readonly class IdentifiableCollection implements \IteratorAggregate, \Countable
{
    /** @var array<string, T> */
    protected array $items;

    /** @param array<array-key, T> $items */
    final public function __construct(
        array $items = [],
    ) {
        $itemsWithKeys = [];
        foreach ($items as $item) {
            $itemsWithKeys[(string) $item->getIdentifier()] = $item;
        }

        $this->items = $itemsWithKeys;
    }

    // Mutation

    /** @param T $item */
    public function add(Identifiable $item): static
    {
        $items = $this->items;
        $items[] = $item;

        /** @psalm-suppress UnsafeGenericInstantiation */
        return new static($items);
    }

    /** @param T $item */
    public function remove(Identifiable $item): static
    {
        return $this->filter(
            /** @param T $currentItem */
            static fn (Identifiable $currentItem) => (string) $currentItem->getIdentifier() !== (string) $item->getIdentifier(),
        );
    }

    /** @param static $collection */
    public function removeCollection(self $collection): static
    {
        return $this->filter(
            /** @param T $currentItem */
            static fn (Identifiable $currentItem) => $collection->notContains($currentItem),
        );
    }

    // Guards

    public function mustContainWithIdentifier(\Stringable $identifier): void
    {
        if (!array_key_exists((string) $identifier, $this->items)) {
            throw new ItemNotInCollection($identifier);
        }
    }

    public function mustNotContainWithIdentifier(\Stringable $identifier): void
    {
        if (array_key_exists((string) $identifier, $this->items)) {
            throw new ItemAlreadyInCollection($identifier);
        }
    }

    public function mustContainAllWithIdentifiers(IdList $identifiers): void
    {
        if ($this->notContainsAllWithIdentifiers($identifiers)) {
            throw new CollectionDoesNotIncludeAll();
        }
    }

    // Getter

    /** @return array<string, T> */
    public function getItems(): array
    {
        return $this->items;
    }

    /** @return T */
    public function withIdentifier(\Stringable $identifier): Identifiable
    {
        if ($this->notContainsWithIdentifier($identifier)) {
            throw new ItemDoesNotExist($identifier);
        }

        return $this->items[(string) $identifier];
    }

    /** @param T $identifier */
    public function contains(Identifiable $identifier): bool
    {
        return array_key_exists((string) $identifier->getIdentifier(), $this->items);
    }

    public function containsWithIdentifier(\Stringable $identifier): bool
    {
        return array_key_exists((string) $identifier, $this->items);
    }

    /** @param T $identifiable */
    public function notContains(Identifiable $identifiable): bool
    {
        return !$this->contains($identifiable);
    }

    public function notContainsWithIdentifier(\Stringable $identifier): bool
    {
        return !$this->containsWithIdentifier($identifier);
    }

    /** @param array<int, T> $identifiables */
    public function containsAll(array $identifiables): bool
    {
        foreach ($identifiables as $identifiable) {
            if ($this->notContains($identifiable)) {
                return false;
            }
        }

        return true;
    }

    public function containsAllWithIdentifiers(IdList $identifiers): bool
    {
        foreach ($identifiers as $identifier) {
            if ($this->notContainsWithIdentifier($identifier)) {
                return false;
            }
        }

        return true;
    }

    public function notContainsAllWithIdentifiers(IdList $identifiers): bool
    {
        return !$this->containsAllWithIdentifiers($identifiers);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /** @return T */
    public function first(): Identifiable
    {
        /** @var string $arrayKey */
        $arrayKey = array_key_first($this->items);

        return $this->items[$arrayKey];
    }

    // Transformer

    /**
     * Psalm doesn't yet realize when a function is pure and when not. To prevent us from marking every single use by hand (which will
     * reduce the readability), we ignore the purity for now and will change the call here to pure-callable as soon as Psalm can handle
     * it.
     *
     * @template R
     *
     * @psalm-param \Closure(T):R $mapFunction
     *
     * @return array<int, R>
     */
    public function map(\Closure $mapFunction): array
    {
        /** @psalm-suppress ImpureFunctionCall */
        return array_values(array_map($mapFunction, $this->items));
    }

    /**
     * Psalm doesn't yet realize when a function is pure and when not. To prevent us from marking every single use by hand (which will
     * reduce the readability), we ignore the purity for now and will change the call here to pure-callable as soon as Psalm can handle
     * it.
     *
     * @param \Closure(T):bool $filterFunction
     *
     * @return static<T>
     */
    public function filter(\Closure $filterFunction): static
    {
        /**
         * @psalm-suppress ImpureFunctionCall
         * @psalm-suppress UnsafeGenericInstantiation
         */
        return new static(array_filter($this->items, $filterFunction));
    }

    /**
     * Psalm doesn't yet realize when a function is pure and when not. To prevent us from marking every single use by hand (which will
     * reduce the readability), we ignore the purity for now and will change the call here to pure-callable as soon as Psalm can handle
     * it.
     *
     * @param \Closure(T):bool $findFunction
     *
     * @return T|null
     */
    public function find(\Closure $findFunction): ?Identifiable
    {
        /** @psalm-suppress ImpureFunctionCall */
        $items = array_values(array_filter($this->items, $findFunction));

        return count($items) > 0
            ? $items[0]
            : null;
    }

    /**
     * Psalm doesn't yet realize when a function is pure and when not. To prevent us from marking every single use by hand (which will
     * reduce the readability), we ignore the purity for now and will change the call here to pure-callable as soon as Psalm can handle
     * it.
     *
     * @param \Closure(T, T):int $sortFunction
     *
     * @return static<T>
     */
    final public function sort(\Closure $sortFunction): static
    {
        $items = $this->items;

        /** @psalm-suppress ImpureFunctionCall */
        uasort($items, $sortFunction);

        /**
         * @psalm-suppress InvalidScalarArgument
         * @psalm-suppress UnsafeGenericInstantiation
         */
        return new static($items);
    }

    /**
     * Psalm doesn't yet realize when a function is pure and when not. To prevent us from marking every single use by hand (which will
     * reduce the readability), we ignore the purity for now and will change the call here to pure-callable as soon as Psalm can handle
     * it.
     *
     * @param \Closure(T):bool $searchFunction
     */
    public function some(\Closure $searchFunction): bool
    {
        return $this->filter($searchFunction)->count() > 0;
    }

    /** @return array<string, T> */
    public function toArray(): array
    {
        return $this->items;
    }

    // Iterator

    /** @return \Iterator<T> */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->items);
    }
}
