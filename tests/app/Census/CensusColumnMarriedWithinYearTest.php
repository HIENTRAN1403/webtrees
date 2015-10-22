<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2015 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fisharebest\Webtrees\Census;

use Fisharebest\Webtrees\Date;
use Fisharebest\Webtrees\Fact;
use Fisharebest\Webtrees\Family;
use Fisharebest\Webtrees\Individual;
use Mockery;

/**
 * Test harness for the class CensusColumnMarriedWithinYear
 */
class CensusColumnMarriedWithinYearTest extends \PHPUnit_Framework_TestCase {
	/**
	 * Delete mock objects
	 */
	public function tearDown() {
		Mockery::close();
	}

	/**
	 * @covers Fisharebest\Webtrees\Census\CensusColumnMarriedWithinYear
	 * @covers Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testMarriedWithinYear() {
		$fact = Mockery::mock(Fact::class);
		$fact->shouldReceive('getDate')->andReturn(new Date('01 DEC 1859'));

		$family = Mockery::mock(Family::class);
		$family->shouldReceive('getFacts')->with('MARR')->andReturn([$fact]);

		$individual = Mockery::mock(Individual::class);
		$individual->shouldReceive('getSpouseFamilies')->andReturn([$family]);

		$census = Mockery::mock(CensusInterface::class);
		$census->shouldReceive('censusDate')->andReturn('01 JUN 1860');

		$column = new CensusColumnMarriedWithinYear($census, '', '');

		$this->assertSame('Y', $column->generate($individual));
	}

	/**
	 * @covers Fisharebest\Webtrees\Census\CensusColumnMarriedWithinYear
	 * @covers Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testNotMarriedWithinYear() {
		$fact = Mockery::mock(Fact::class);
		$fact->shouldReceive('getDate')->andReturn(new Date('01 JAN 1859'));

		$family = Mockery::mock(Family::class);
		$family->shouldReceive('getFacts')->with('MARR')->andReturn([$fact]);

		$individual = Mockery::mock(Individual::class);
		$individual->shouldReceive('getSpouseFamilies')->andReturn([$family]);

		$census = Mockery::mock(CensusInterface::class);
		$census->shouldReceive('censusDate')->andReturn('01 JUN 1860');

		$column = new CensusColumnMarriedWithinYear($census, '', '');

		$this->assertSame('', $column->generate($individual));
	}

	/**
	 * @covers Fisharebest\Webtrees\Census\CensusColumnMarriedWithinYear
	 * @covers Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testNoMarriage() {
		$family = Mockery::mock(Family::class);
		$family->shouldReceive('getFacts')->with('MARR')->andReturn([]);

		$individual = Mockery::mock(Individual::class);
		$individual->shouldReceive('getSpouseFamilies')->andReturn([$family]);

		$census = Mockery::mock(CensusInterface::class);
		$census->shouldReceive('censusDate')->andReturn('01 JUN 1860');

		$column = new CensusColumnMarriedWithinYear($census, '', '');

		$this->assertSame('', $column->generate($individual));
	}

	/**
	 * @covers Fisharebest\Webtrees\Census\CensusColumnMarriedWithinYear
	 * @covers Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testNoSpouseFamily() {
		$individual = Mockery::mock(Individual::class);
		$individual->shouldReceive('getSpouseFamilies')->andReturn([]);

		$census = Mockery::mock(CensusInterface::class);
		$census->shouldReceive('censusDate')->andReturn('01 JUN 1860');

		$column = new CensusColumnMarriedWithinYear($census, '', '');

		$this->assertSame('', $column->generate($individual));
	}
}
