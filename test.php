<?php

class A {
	public function hello() {
		return "hello";
	}
}

function test(A $value) {
	if ($value) {
		echo $value->hello();
	}
	else {
		echo "no value";
	}

	echo "\n";
}


test(new A());
test(null);
?>
