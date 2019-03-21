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

    create unique_index(:resolutions, [:name, :resp_group, :resolved_date], name: :name_per_resolved_date_index)
  end
end
