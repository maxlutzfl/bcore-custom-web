/** Setup */
var host = 'managed.brandco.com',
	username = 'sosaphil',
	remoteDest = 'public_html/somewhere/';

/** Require */
var plan = require('flightplan');

/** Target */
plan.target('production', [{
	host: host,
	username: username,
	agent: process.env.SSH_AUTH_SOCK
}]);

/** Deploy */
plan.local(function(local) {
	local.exec('cd web');
	var filesToCopy = local.exec('git ls-files', {
		exec: {
			maxBuffer: 10000 * 1024
		},
		silent: true
	});
	local.exec('cd ../');
	local.transfer(filesToCopy, remoteDest);
});



