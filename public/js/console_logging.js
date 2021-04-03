function log2laravel(level, string) {
	$.post('/log2laravel', {
		level, string
	})
	.fail((e) => console.error(e));
}
