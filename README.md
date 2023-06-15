# Awakening Church WordPress

This repository contains the source code for awakeningchurch.com and the
associated development and production server infrastructure.

This project is based on [Bedrock][] and [Trellis][] by [Roots][].
The Bedrock based WordPress installation is in `site/` and the
Trellis based server infrastructure is in `trellis/`. For more
about how to understand these files, please see the [Bedrock][]
and [Trellis][] documentation.

## Login from WP-CLI

We use G Workspace SSO for login which is not available in development.
An easy alternative is to use magic login links:

```shell
trellis vm shell -- sudo -u vagrant sh -c 'wp login install --activate --yes'
trellis vm shell -- sudo -u vagrant sh -c "wp @development login as 'Reid Burke'"
```

## Sync database

```shell
trellis exec ./bin/sync.sh production awakeningchurch.com db down
trellis exec ./bin/sync.sh production awakeningchurch.com db up
```

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
