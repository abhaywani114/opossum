	function atm_money(num) {
		if (num.toString().length == 1) {
			return '00.0' + num.toString()
		} else if (num.toString().length == 2) {
			return '00.' + num.toString()
		} else if (num.toString().length == 3) {
			return '0' + num.toString()[0] + '.' + num.toString()[1] +
				num.toString()[2];
		} else if (num.toString().length >= 4) {
			return num.toString().slice(0, (num.toString().length - 2)) +
				'.' + num.toString()[(num.toString().length - 2)] +
				num.toString()[(num.toString().length - 1)];
		}
	}

