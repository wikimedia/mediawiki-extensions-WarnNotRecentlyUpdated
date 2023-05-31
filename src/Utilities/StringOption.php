<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @file
 */

namespace MediaWiki\Extension\WarnNotRecentlyUpdated\Utilities;

use LogicException;

class StringOption {

	private OptionState $state;
	private string $value;

	public static function fromNone(): static {
		$result = new static;
		$result->state = OptionState::None;

		return $result;
	}

	public static function from( string $value ): static {
		$result = new static;
		$result->state = OptionState::Some;
		$result->value = $value;

		return $result;
	}

	public function isSome(): bool {
		return $this->state == OptionState::Some;
	}

	public function isNone(): bool {
		return $this->state == OptionState::None;
	}

	public function get(): string {
		if ( $this->isNone() ) {
			throw new LogicException;
		}

		return $this->value;
	}
}
