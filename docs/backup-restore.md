# Backup and restore strategy

Back up both MariaDB and uploaded media. A database-only backup cannot restore the media library, and a file-only backup cannot restore pages, users, submissions, settings, versions, or audit records.

## Schedule and retention

- Daily automated database backup retained for 14 days
- Weekly full application/media backup retained for eight weeks
- Monthly encrypted off-host backup retained for twelve months
- Additional backup immediately before every deployment or migration

HostNext JetBackup may provide the first copy, but at least one encrypted copy must be stored outside the hosting account.

## Restore test

At least quarterly:

1. Restore the latest database backup into a separate non-production database.
2. Restore media into a separate non-public test location.
3. Configure a temporary test deployment with separate credentials.
4. Verify administrator login, published and legal pages, navigation, media, enquiries, waitlist, page versions, and audit records.
5. Record the restore date, operator, backup used, elapsed time, and corrective actions.

A backup is not verified until this restore exercise succeeds. Encrypt off-host backups, restrict operator access, never store archives under `public_html`, and apply approved retention periods to personal data.
