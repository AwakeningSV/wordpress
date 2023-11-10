# Awakening Church WordPress

This repository contains the source code for awakeningchurch.com and the
associated development and production server infrastructure.

This project is based on [Bedrock][] and [Trellis][] by [Roots][].
The Bedrock based WordPress installation is in `site/` and the
Trellis based server infrastructure is in `trellis/`. For more
about how to understand these files, please see the [Bedrock][]
and [Trellis][] documentation.

## Quickstart

```shell
brew install roots/tap/trellis-cli
cd trellis # Required to provision the correct VM, see .trellis/cli.yml
# Edit ansible.cfg to include IdentitiesOnly=yes SSH config
trellis vm start
# If provisioning fails, run:
trellis provision development
# Revert the changes to ansible.cfg to deploy to remote servers
```

### Provisioning for a single tag

If the provision fails, you can retry with a single tag to rerun just that part.

```shell
trellis provision --tags xdebug development
```

## Stop before system sleep

Due to [known Lima VM issues with disk corruption][vmd], it is recommended to stop the VM before putting the computer to sleep.

```shell
trellis vm stop
```

[vmd]: https://github.com/lima-vm/lima/issues/1288

## Sync database

```shell
trellis exec ./bin/sync.sh production awakeningchurch.com db down
trellis exec ./bin/sync.sh production awakeningchurch.com db up
```

## Login from WP-CLI

We use G Workspace SSO for login which is not available in development.
An easy alternative is to use magic login links:

```shell
trellis vm shell -- sudo -u vagrant sh -c 'wp login install --activate --yes'
trellis vm shell -- sudo -u vagrant sh -c "wp login as 'Reid Burke'"
```

## Use Composer

```shell
trellis vm shell -- sudo -u vagrant sh -c 'composer update'
# WordPress upgrade example
trellis vm shell -- sudo -u vagrant sh -c 'composer upgrade roots/wordpress:^6.2 --with-all-dependencies'
```

### Issues with Composer installation

Issues may include:
- Content-Length mismatch during WP-CLI install
- 404 when updating

```shell
trellis vm shell
sudo su vagrant
vi /home/vagrant/.composer/config.json
```

Apply config: https://gist.github.com/oanhnn/112f68e5b91a7dac7641bcd8b0ab13ac

```json
{
  "config": {
    "github-protocols": [
      "https,ssh"
    ]
  },
  "repositories": {
    "packagist": {
      "type": "composer",
      "url": "https://packagist.org"
    }
  }
}
```

## Problems running php

When composer fails with this error:

```
Library not loaded: /opt/homebrew/opt/libavif/lib/libavif.15.dylib
```

You need to reinstall `gd` with `brew reinstall gd`.

See: https://stackoverflow.com/a/77222642

## Azure DevOps SSH key setup

You may have issues working with Azure DevOps repos if
you use more than one SSH key. Ensure you only have a
single valid IdentityFile for `ssh.dev.azure.com` in
your `~/.ssh/config`. For more, see the [Azure docs][az].

If you do not have this setup correctly, you may get this
error during Composer install:

```
remote: Public key authentication failed.
```

## License

MIT, see LICENSE.

[Bedrock]: https://roots.io/bedrock/
[Trellis]: https://roots.io/trellis/
[Roots]: https://roots.io
[az]: https://docs.microsoft.com/en-us/azure/devops/repos/git/use-ssh-keys-to-authenticate?view=azure-devops&tabs=current-page#q-i-have-multiple-ssh-keys--how-do-i-use-different-ssh-keys-for-different-ssh-servers-or-repos
[fix]: https://github.com/hashicorp/vagrant/issues/12583#issuecomment-975070111
