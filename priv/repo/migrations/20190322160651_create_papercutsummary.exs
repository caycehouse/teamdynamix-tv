defmodule TeamdynamixTv.Repo.Migrations.CreatePapercutsummary do
  use Ecto.Migration

  def change do
    create table(:papercutsummary) do
      add :name, :string
      add :status, :string
      add :status_color, :string

      timestamps()
    end

    create unique_index(:papercutsummary, [:name])
  end
end
