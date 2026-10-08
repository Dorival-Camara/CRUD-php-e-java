package br.maqclick.dao;

import br.maqclick.conexao.Conexao;
import br.maqclick.model.Equipamento;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

public class EquipamentoDAO {

    public void cadastrar(Equipamento equipamento) {
        String sql =
            "INSERT INTO equipamento (nome_equipamento, descricao_equipamento, status_equipamento, id_categoria) VALUES (?, ?, ?, ?)";
        try {
            Connection conexao = Conexao.conectar();
            PreparedStatement ps = conexao.prepareStatement(sql);
            ps.setString(1, equipamento.getNome());
            ps.setString(2, equipamento.getDescricao());
            ps.setString(3, equipamento.getStatus());
            ps.setInt(4, equipamento.getIdCategoria());
            ps.executeUpdate();
            ps.close();
            conexao.close();
        } catch (Exception e) {
            System.out.println("Erro: " + e.getMessage());
        }
    }

    public List<Equipamento> listar() {
        List<Equipamento> lista = new ArrayList<>();
        String sql = "SELECT * FROM equipamento ORDER BY nome_equipamento";
        try {
            Connection conexao = Conexao.conectar();
            PreparedStatement ps = conexao.prepareStatement(sql);
            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                Equipamento equipamento = new Equipamento();
                equipamento.setId(rs.getInt("id_equipamento"));
                equipamento.setNome(rs.getString("nome_equipamento"));
                equipamento.setDescricao(rs.getString("descricao_equipamento"));
                equipamento.setStatus(rs.getString("status_equipamento"));
                equipamento.setIdCategoria(rs.getInt("id_categoria"));
                lista.add(equipamento);
            }
            rs.close();
            ps.close();
            conexao.close();
        } catch (Exception e) {
            System.out.println("Erro: " + e.getMessage());
        }
        return lista;
    }

    public void alterar(Equipamento equipamento) {
        String sql =
            "UPDATE equipamento SET nome_equipamento = ?, descricao_equipamento = ?, status_equipamento = ?, id_categoria = ? WHERE id_equipamento = ?";
        try {
            Connection conexao = Conexao.conectar();
            PreparedStatement ps = conexao.prepareStatement(sql);
            ps.setString(1, equipamento.getNome());
            ps.setString(2, equipamento.getDescricao());
            ps.setString(3, equipamento.getStatus());
            ps.setInt(4, equipamento.getIdCategoria());
            ps.setInt(5, equipamento.getId());
            ps.executeUpdate();
            ps.close();
            conexao.close();
        } catch (Exception e) {
            System.out.println("Erro: " + e.getMessage());
        }
    }

    public void excluir(int id) {
        String sql = "DELETE FROM equipamento WHERE id_equipamento = ?";
        try {
            Connection conexao = Conexao.conectar();
            PreparedStatement ps = conexao.prepareStatement(sql);
            ps.setInt(1, id);
            ps.executeUpdate();
            ps.close();
            conexao.close();
        } catch (Exception e) {
            System.out.println("Erro: " + e.getMessage());
        }
    }
}
