<?php

test('get all categories', function () {
    $this->get(route('main_category.index'))->assertOK();
});
