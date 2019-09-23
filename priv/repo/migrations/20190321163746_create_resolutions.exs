defmodule TeamdynamixTv.Repo.Migrations.CreateResolutions do
  use Ecto.Migration

  def change do
    create table(:resolutions) do
      add :name, :string
      add :closes, :integer
      add :resp_group, :string
      add :resolved_date, :utc_datetime

      timestamps()
    end
  end
end
